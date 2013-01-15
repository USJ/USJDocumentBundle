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

    public function createLink($class = null, $objectId = null)
    {
        $link = new $this->class;
        if(!is_null($class) && !is_null($objectId)) {
            $link->setClass($class)->setObjectId($objectId);
        }
        return $link;
    }

    public function linkObject($document, $object)
    {
        $link = new Link();
        $link->setClass(get_class($object));
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
