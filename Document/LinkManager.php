<?php
namespace MDB\DocumentBundle\Document;

class LinkManager
{
    protected $dm;

    protected $class;

    public function __construct($dm, $class)
    {
        $this->dm = $dm;
        $this->class = $class;
    }

    public function createLink($objectToLink = null)
    {
        if (!is_null($objectToLink)) {
            $classNames = $this->dm //dm
            ->getConfiguration()
            ->getMetadataDriverImpl()
            ->getAllClassNames();

            if (!in_array(get_class($objectToLink), $classNames)) {
                throw new \RuntimeException("Object class was not mapped, cannot use for linking.");
            }

            $link = new $this->class;
            $link->setClass(get_class($objectToLink));
            $link->setObjectId($objectToLink->getId());

            return $link;
        }

        $link = new $this->class;

        return $link;
    }

    public function linkObject($document, $object)
    {
        $link = new $class;
        $documentMetadata = $this->dm->getClassMetadata(get_class($document));
        $link->setClass($documentMetadata->getName());
        $link->setObjectId($object->getId());
        $document->addLinks($link);
        $this->dm->flush($document);
    }

    public function findLinkByObject($document, $object)
    {
        foreach ($document->getLinks() as $link) {
            if ($link->getObjectId() == $object->getId()) {
                return $link;
            }
        }
    }

    public function findLinkByObjectId($document, $objectId )
    {
        foreach ($document->getLinks() as $link) {
            if ($link->getObjectId() == $objectId) {
                return $link;
            }
        }
    }

    public function isNewLink($document, $newLink)
    {
        $links = $document->getLinks();
        foreach ($links as $link) {
            if ($link->getClass() == $newLink->getClass() && $link->getObjectId() == $newLink->getObjectId()) {
                return false;
            }
        }

        return true;
    }

    public function findAllLinks($value='')
    {
        # code...
    }
}
