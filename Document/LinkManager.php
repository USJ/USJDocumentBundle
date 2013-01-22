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

    public function createLink($objectToLink = null)
    {
        if(!is_null($objectToLink)) {
            $classNames = $this->dm //DocumentManager
            ->getConfiguration()
            ->getMetadataDriverImpl()
            ->getAllClassNames();

            if(!in_array(get_class($object), $classNames)) {
                throw new \RuntimeException("Object class was not mapped, cannot use for linking.");
            }

            $link = new $this->class;
            $link->setClass(get_class($object));
            $link->setObjectId($object->getId());
            return $link;
        }

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
