<?php 
/**
 * Controller for MDB document
 */
namespace MDB\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use MDB\DocumentBundle\Document\File;

use MDB\DocumentBundle\Form\Type\DocumentType;
class DocumentController extends Controller
{
 	
 	public function indexAction() 
 	{
        $qb = $this->get('doctrine.odm.mongodb.document_manager')->createQueryBuilder('MDBDocumentBundle:Document');
        $request = $this->getRequest();
        $q = $request->query->get('q');

        $query = isset($q) ? $this->container->get('foq_elastica.finder.mdb_document.document')->createPaginatorAdapter($q) : $qb->getQuery() ;

        if($this->container->has('knp_paginator')) {
            $paginator = $this->container->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $this->get('request')->query->get('page', 1)/*page number*/,
                10
            ); 
        }
        return $this->render("MDBDocumentBundle:Document:index.html.twig", array("pagination" => $pagination));
 	}

    public function newAction()
    {
        $request = $this->getRequest();

        $document = $this->container->get('mdbdocument_document_manager')->createDocument();

        $form = $this->createForm(new DocumentType(), $document);
        if($request->getMethod() == 'POST') {
            $form->bind($request);
            if($form->isValid()) {
                $dm = $this->container->get('doctrine_mongodb')->getManager();

                $files = array();

                $upload =$form['files']->getData();
                $file = new File();
                $file->setFile($upload->getPathname());
                $file->setFilename($upload->getClientOriginalName());
                $file->setMimeType($upload->getClientMimeType());
                // $dm->persist($file);
                $files[] = $file;
                $document->setFiles($files);

                $dm->persist($document);
                $dm->flush();
            }
            return $this->redirect($this->generateUrl('mdb_document_document_index'));              

        }
        return $this->render("MDBDocumentBundle:Document:new.html.twig",array("form"=>$form->createView())); 
    }

    public function showAction($id)
    {
        $document = $this->container->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('MDBDocumentBundle:Document')
            ->findOneById($id);
        return $this->render("MDBDocumentBundle:Document:show.html.twig", array("document" => $document));   
    }

    public function editAction($id)
    {
        $request = $this->getRequest();

        $document = $this->container->get('doctrine_mongodb')
            ->getRepository("MDBDocumentBundle:Document")
            ->findOneById($id);

        $form = $this->createForm(new DocumentType(), $document);
        if($request->getMethod() == 'POST') {
            $form->bind($request);
            if($form->isValid()) {
                $dm = $this->get('doctrine_mongodb')->getManager();
                $upload =$form['files']->getData();
                $file = new File();
                $file->setFile($upload->getPathname());
                $file->setFilename($upload->getClientOriginalName());
                $file->setMimeType($upload->getClientMimeType());
                // $dm->persist($file);
                $document->addFiles($file);
                $dm->flush($document);
            }
            return $this->redirect($this->generateUrl('mdb_document_document_index'));              

        }
        return $this->render("MDBDocumentBundle:Document:edit.html.twig",array("document" => $document,"form"=>$form->createView())); 

    }

    public function deleteAction($id)
    {
        $document = $this->container->get('doctrine_mongodb')
            ->getRepository("MDBDocumentBundle:Document")
            ->findOneById($id);
        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $dm->remove($document);
        $dm->flush();

        return $this->redirect($this->generateUrl('mdb_document_document_index'));
    }

    public function fileAction()
    {
        $request = $this->getRequest();

        $qId = $request->query->get('id');
        $qFilename = $request->query->get('filename');
        $download = $request->query->get('d');
        $qFormat = $request->query->get('format');


        $fileRepo = $this->container
            ->get('doctrine_mongodb')
            ->getManager()
            ->getRepository("MDBDocumentBundle:File");

        if(isset($qId)) {
            $file = $fileRepo->findOneById($qId);
        }elseif(isset($qFilename)){
            $file = $fileRepo->findOneByFilename($qFilename);
        }        

        if (null === $file) {
            throw $this->createNotFoundException(sprintf('File with filename "%s" could not be found', $filename));
        }

        return $this->container->get('mdb_document.file_response_factory')->createResponse($file, $download, $qFormat);
    }

}