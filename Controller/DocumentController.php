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
        $documents = $this->container->get('mdb_document.manager.document')->findAllDocuments();
        return $this->render("MDBDocumentBundle:Document:index.html.twig", array('documents' => $documents));;
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
                $this->container->get('mdb_document.manager.document')->saveDocument($form->getData());
                $this->setFlash($request->getSession(), 'success', 'Document created success');
                return $this->redirect($this->generateUrl('mdb_document_document_index'));
            }
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
        $fileForm = $this->container->get('mdb_document.form_factory.file')->createForm();

        if($version) {
            $file = $document->getFile($version);
        }else{
            $file = $document->getFile();
        } 

        if($request->isXmlHttpRequest()) {
            return $this->render("MDBDocumentBundle:Document:show.doc_embed.html.twig", array("document" => $document));   
        }

        return $this->render("MDBDocumentBundle:Document:show.".$format.".twig", array(
            "document" => $document, 
            "file" => $file,
            "fileForm" => $fileForm->createView()
            )
        );   
    }

    /**
     * allow user to edit, return edit view for normal access, accept PUT request to update
     * 
     * @Route("/documents/{id}/edit", name="mdb_document_document_edit")
     * @Method({"GET","PUT"})
     */
    public function editAction($id)
    {
        $request = $this->getRequest();

        $document = $this->container->get('mdb_document.manager.document')->findDocumentById($id);
        $form = $this->container->get('mdb_document.form_factory.document')->createForm();
        $form->setData($document);
        
        if($request->getMethod() == 'PUT') {
            $form->bind($request);
            if($form->isValid()) {
                $this->container->get('mdb_document.manager.document')->saveDocument($form->getData());
            }
            return $this->redirect($this->generateUrl('mdb_document_document_show', array('id' => $document->getId())));              
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
     * @Route("/documents/{documentId}/files", name="mdb_document_document_files")
     * @Method({"GET", "POST"})
     */
    public function filesAction(Request $request, $documentId)
    {
        $document = $this->container->get("mdb_document.manager.document")->findDocumentById($documentId);

        // new file upload
        $file = $this->container->get("mdb_document.manager.file")->createFile();
        $form = $this->container->get("mdb_document.form_factory.file")->createForm();
        $form->setData($file);

        if($request->getMethod() == 'POST') {
            $form->bind($request);
            if($form->isValid()) {
                $document->addFile($form->getData());
                $this->container->get("mdb_document.manager.document")->saveDocument($document);
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
        $document = $this->container->get('mdb_document.manager.document')->findDocumentById($id);

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