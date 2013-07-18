<?php
namespace MDB\DocumentBundle\Document;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use MDB\DocumentBundle\Model\DocumentManager as BaseDocumentManager;
use MDB\DocumentBundle\Events;
use MDB\DocumentBundle\Event\LinkEvent;
use Doctrine\ODM\MongoDB\DocumentManager as ODMDocumentManager;

/**
* Class act as a service to access documents
*/
class DocumentManager extends BaseDocumentManager
{
    /** @var ODM MongoDB Document Manager */
    protected $dm;
    /** @var class  */
    protected $class;

    protected $repository;

    public function __construct(EventDispatcherInterface $dispatcher, ODMDocumentManager $dm, $class)
    {
        parent::__construct($dispatcher);

        $this->dm = $dm;
        $this->class = $class;
        $this->repository = $this->getRepository();
    }

    /**
     * Return the repository used by this manager
     *
     * @return DocumentRepository
     */
    public function getRepository()
    {
        if (!$this->repository) {
            return $this->dm->getRepository($this->class);
        }

        return $this->repository;
    }

    /**
     * @return Document $document object
     */
    public function createDocument()
    {
        $document = new $this->class;

        return $document;
    }

    public function deleteDocument(\MDB\DocumentBundle\Document\Document $document)
    {
        $this->dm->remove($document);
        $this->dm->flush();
    }

    // public function findDocumentsByLink(Link $link)
    // {
    //     return $this->repository->findDocumentsByClassAndObjectId($link->getClass(), $link->getObjectId());
    // }

    // public function unlinkObject($document, $link)
    // {
    //     $originalLinkCount = count($document->getLinks());
    //     // find the link object
    //     $document->removeLink($link);

    //     if (!$originalLinkCount > count($document->getLinks())) {
    //         throw new \RuntimeException("Document unlink failed");
    //     }

    //     $this->saveDocument($document);

    //     if (count($document->getLinks()) < 1) {
    //         $this->removeDocument($document);
    //     }
    // }

    // public function linkObject(\MDB\DocumentBundle\Document\Document $document, $object)
    // {
    //     $objectClass = get_class($object);
    //     if (!$this->isMappedClass($objectClass)) {
    //         throw new \RuntimeException(sprintf("%s class was not mapped, cannot use for linking.", $objectClass));
    //     }
    //     $this->doLinkObject($document, $object);
    // }

    public function createPreLinkedDocument($object)
    {
        $objectClass = get_class($object);
        if (!$this->isMappedClass($objectClass)) {
            throw new \RuntimeException(sprintf("%s class was not mapped, cannot use for linking.", $objectClass));
        }

        $document = $this->createDocument();
        $link = new $this->linkClass;
        $link->setClass($objectClass)
            ->setObjectId($object->getId());
        $document->addLink($link);

        return $document;
    }

    public function findDocuments()
    {
        return $this->repository->findAll();
    }

    public function findAllDocuments()
    {
        return $this->repository->findAll();
    }

    public function getDocumentManager()
    {
        return $this->dm;
    }

    // protected function doLinkObject($document, $object)
    // {
    //     $link = new $this->linkClass;
    //     $link->setClass(get_class($object))
    //         ->setObjectId($object->getId());

    //     $this->dispatcher->dispatch(new LinkEvent($link), Events::DOCUMENT_PRE_LINK );

    //     $document->addLink($link);
    //     $this->doSaveDocument($document);

    //     $this->dispatcher->dispatch(new LinkEvent($link), Events::DOCUMENT_POST_LINK );

    // }

    protected function doSaveDocument($document)
    {
        $this->dm->persist($document);
        $this->dm->flush();
    }

    public function isMappedClass($class)
    {
        $classNames = $this->dm //DocumentManager
            ->getConfiguration()
            ->getMetadataDriverImpl()
            ->getAllClassNames();

        if (!in_array($class, $classNames)) {
            return false;
        }

        return true;
    }

    public function removeDocument($document)
    {
        $this->doRemoveDocument($document);
    }

    public function doRemoveDocument($document)
    {
        $this->dm->remove($document);
        $this->dm->flush();
    }

    public function getClass()
    {
        return $this->class;
    }

    public function isNewDocument($document)
    {
        return !$this->dm->getUnitOfWork()->isInIdentityMap($document);
    }
}
