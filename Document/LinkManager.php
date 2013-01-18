<?php 
namespace MDB\DocumentBundle\Document;

class LinkManager
{
    protected $documentManager;
    
    protected $class;

    public function __construct($documentManager, $class)
    {
        $this->documentManager = $documentManager;
        $this->class = $class;
    }

    public function createLink()
    {
        $link = new $this->class;
        return $link;
    }

    public function linkObject($document, $object)
    {
        $link = new Link();
        $documentMetadata = $this->dm->getClassMetadata(get_class($document));
        $link->setClass($documentMetadata->getName());
        $link->setObjectId($object->getId());
        $document->addLinks($link);
        $this->documentManager->flush($document);
    }


    public function isNewLink($document, $newLink)
    {
        $links = $document->getLinks();
        foreach($links as $link) {
            if($link->getClass() == $newLink->objectClass && $link->getObjectId() == $newLink->objectId) {
                return false;
            }
        }
        return true;
    }
}
