<?php 
namespace MDB\DocumentBundle\Document;

class LinkManager
{
    private $documentManager;

    public function __construct($documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function linkObject($document, $object)
    {
        $link = new Link();
        $link->setClass(get_class($object));
        $link->setObjectId($object->getId());
        $document->addLinks($link);
        $this->documentManager->flush($document);
    }
}
