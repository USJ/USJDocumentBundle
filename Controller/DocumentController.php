<?php 
/**
 * Controller for MDB document
 */
namespace MDB\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use MDB\DocumentBundle\Document\File;
use MDB\DocumentBundle\Document\Link;

use MDB\DocumentBundle\Form\Type\DocumentType;
use MDB\DocumentBundle\Form\Type\FileType;

class DocumentController extends Controller
{
 	/**
     * @Route("/documents.{_format}", name="mdb_document_document_index", defaults={"_format" = "html"},options={"expose" = true})
     * @Method({"GET"})
     */
 	public function indexAction(Request $request) 
 	{
        $q = $request->query->get('q');
        $format = $request->getRequestFormat('html');

        $qb = $this->get('doctrine.odm.mongodb.document_manager')->createQueryBuilder('MDBDocumentBundle:Document');
        // TODO make search provider configurable.
        $query = isset($q) ? 
            $this->container->get('foq_elastica.finder.mdb_document.document')->createPaginatorAdapter($q) : 
            $qb->getQuery() ;

        // TODO make pagination provider configurable.
        if($this->container->has('knp_paginator')) {
            $paginator = $this->container->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $this->get('request')->query->get('page', 1)/*page number*/,
                10
            ); 
        }

        if(isset($q) && count($pagination) < 1 && $format === 'html') {
            $request->getSession()->getFlashBag()->add('notice', 'No result was found.');
        }

        return $this->render("MDBDocumentBundle:Document:index.".$format.".twig", 
            array(
                'pagination' => $pagination, 
                'search_term' => $q
            )
        );
 	}

    /**
     * Action to render form and handle post request.
     * 
     * @Route("/documents/new", name="mdb_document_document_new")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request)
    {
        // TODO possible to do it via a temporary id created before upload real document
        $document = $this->container->get('mdb_document.manager.document')->createDocument();
        $form = $this->container->get("mdb_document.form_factory.document")->createForm();
        $form->setData($document);
        
        if($request->getMethod() == 'POST') {
            $form->bind($request);

            if($form->isValid()) {
                $dm = $this->container->get('doctrine_mongodb')->getManager();
                $document->addUploadedFile($form['file']->getData());

                $object_class = urldecode($request->request->get('object_class'));
                $object_id = $request->request->get('object_id');

                if($object_id && $object_class) {
                    $link = new Link();
                    $link->setClass($object_class)->setObjectId($object_id);
                    $document->addLinks($link);
                }
                $dm->persist($document);
                $dm->flush();
            }
            return $this->redirect($request->headers->get('referer'));              

        }
        return $this->render("MDBDocumentBundle:Document:new.html.twig",array("form"=>$form->createView())); 
    }

    /**
     * @Route("/documents/new_linked_document", name="mdb_document_document_new_linked_document")
     * @Method({"POST"})
     */
    public function newPreLinkedDocumentAction(Request $request)
    {
        $form = $this->get('mdb_document.form_factory.pre_linked_document')->createForm();
        $form->bind($request);
        if($form->isValid()) {
            $document = $form->getData();
            $this->get('mdb_document.manager.document')->saveDocument($document);
            $this->setFlash($request->getSession(), 'success', 'Adding document success.');
            return $this->redirect($request->headers->get('referer'));              
        }
        $this->setFlash($request->getSession(), 'notice', 'Adding document failed.');
        return $this->redirect($request->headers->get('referer'));              
    }

    /**
     * @Route("/documents/{id}.{_format}", name="mdb_document_document_show", defaults={"_format" =  "html"},options={"expose" = true})
     * @Method({"GET"})
     */
    public function showAction(Request $request, $id)
    {
        $format = $request->getRequestFormat('html');
        $version = $request->query->get('version');

        $document = $this->container->get('mdb_document.manager.document')->findDocumentById($id);

        if($version) {
            $file = $document->getFile($version); 
        }else{
            $file = $document->getFile();
        } 

        if($request->isXmlHttpRequest()) {
            return $this->render("MDBDocumentBundle:Document:show.doc_embed.html.twig", array("document" => $document));   
        }

        return $this->render("MDBDocumentBundle:Document:show.".$format.".twig", array("document" => $document, 'file' => $file));   
    }

    /**
     * allow user to edit, return edit view for normal access, accept PUT request to update
     * @Route("/documents/{id}/edit", name="mdb_document_document_edit")
     * @Method({"GET","PUT"})
     */
    public function editAction($id)
    {
        $request = $this->getRequest();
        $dm = $this->get('doctrine_mongodb')->getManager();

        $document = $dm
            ->getRepository("MDBDocumentBundle:Document")
            ->findOneById($id);

        $form = $this->createForm(new DocumentType(), $document);
        if($request->getMethod() == 'PUT') {
            $form->bind($request);
            if($form->isValid()) {
                $document->addUploadedFile($form['file']->getData());
                $dm->flush($document);
            }
            return $this->redirect($this->generateUrl('mdb_document_document_index'));              

        }
        return $this->render("MDBDocumentBundle:Document:edit.html.twig", 
            array(
                "document" => $document,
                "form"=>$form->createView()
            )
        ); 

    }

    /**
     * Helps manage the files within a document
     * 
     * @Route("/documents/{id}/files", name="mdb_document_document_files")
     * @Method({"GET", "POST"})
     */
    public function filesAction(Request $request, $id)
    {
        $dm = $this->container->get('doctrine_mongodb');

        $document = $this->container->get("mdb_document.manager.document")->findDocumentById($id);

        // new file upload
        $file = new File();
        $form = $this->createForm(new FileType(), $file);

        if($request->getMethod() == 'POST') {
            $form->bind($request);
            if($form->isValid()) {
                $file = new File();
                $document->addUploadedFile($form['file']->getData());
                $dm->flush($document);
            }

            return $this->redirect($this->generateUrl('mdb_document_document_show', array('id' => $document->getId())));

        }   
        return $this->render("MDBDocumentBundle:Document:files.html.twig", 
            array(
                "form" => $form->createView(), 
                "document" => $document
            ) 
        );             
    }

    /**
     * @Route("/documents/{id}/delete", name="mdb_document_document_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $document = $this->container->get('doctrine_mongodb')
            ->getRepository("MDBDocumentBundle:Document")
            ->findOneById($id);

        $this->container->get('mdb_document.manager.document')->deleteDocument($document);

        return $this->redirect($request->headers->get('referer'));              
    }

    /**
     * Use for creating link to other objects.
     * 
     * @Route("/documents/{documentId}/links", name="mdb_document_document_links", options={"expose" = true})
     * @Method({"GET","POST"})
     */
    public function linksAction(Request $request, $documentId)
    {
        $document = $this->container->get('mdb_document.manager.document')->findDocumentById($documentId);

        if($request->getMethod() == 'POST'){
            $objectClass = urldecode($request->request->get('object_class'));
            $objectId = $request->request->get('object_id');

            if($request->getContentType() === 'json') {
                $json = json_decode($request->getContent(), true);
                $objectClass = $json['object_class'];
                $objectId = $json['object_id'];
            }

            if(!isset($objectClass) || !isset($objectId)) {
                throw new RuntimeException('Object Class and Object Id have to be defined');
            }

            $object = $this->container->get('doctrine_mongodb')
                ->getRepository($objectClass)
                ->findOneById($objectId);

            $linkManager = $this->container->get('mdb_document.manager.link');
            $link = $linkManager->createLink($objectClass, $objectId);

            if($linkManager->isNewLink($document, $link)) {
                $this->container->get('mdb_document.manager.link')->linkObject($document, $object);
            }

            if($request->getContentType() === 'json') {
                $serializer = $this->container->get('jms_serializer');
                return new Response($serializer->serialize($document, 'json'),200);
            }else{
                $request->getSession()->getFlashBag()->set('notice', 'Links created successfully');
            }
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirect($this->generateUrl('mdb_document_document_show', array('id' => $document->getId()) ));
    }

    /**
     * @Route("/documents/{documentId}/links", name="mdb_document_document_links_create", options={"expose" = true})
     */
    public function createLinksAction()
    {
        # code...
    }

    private function setFlash($session, $type, $message) 
    {
        $session->getFlashBag()->add($type,$message);
    }

}