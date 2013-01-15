<?php
namespace MDB\DocumentBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use MDB\DocumentBundle\Document\File;
use MDB\DocumentBundle\Document\Document;

use MDB\DocumentBundle\Form\Type\DocumentType;

class PartialController extends Controller
{
    public function renderCountAction($linkObject)
    {
        return new Response(count($this->findDocumentsByLinkObject($linkObject)), 200);
    }

    public function renderIndexAction($linkObject, $markup = "table")
    {
        return $this->render('MDBDocumentBundle:Partial:documentIndex.'.$markup.'.html.twig', array(
                'documents' => $this->findDocumentsByLinkObject($linkObject)
            ));
    }


    public function renderUploadBoxAction($linkObject)
    {
        $document = new Document();
        $form = $this->createForm(new DocumentType(), $document);
        return $this->render('MDBDocumentBundle:Partial:uploadBox.html.twig', array('form' => $form->createView(), 'linkObject' => $linkObject));
    }

    private function findDocumentsByLinkObject($linkObject)
    {
        $repo = $this->container->get('mdb_document.manager.document')->getRepository();
        return $repo->findDocumentsByClassAndObjectId($linkObject->getClass(), $linkObject->getId());
    }
}