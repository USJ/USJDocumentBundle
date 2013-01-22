<?php
namespace MDB\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager as ODMDocumentManager ;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use MDB\DocumentBundle\Model\DocumentManager as BaseDocumentManager;

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
        $this->repository = $this->dm->getRepository($class);
	}

    /**
     * @return Document $document object
     */ 
    public function createDocument()
    {
        $document = new $this->class;
        return $document;
    }

    public function deleteDocument($document)
    {
        $this->dm->remove($document);
        $this->dm->flush();
    }

    public function findDocumentsByLink(Link $link)
    {
        return $this->repository->findDocumentsByClassAndObjectId($link->getClass(), $link->getObjectId());
    }

    public function linkObject(\MDB\DocumentBundle\Document\Document $document, $object)
    {
        $objectClass = get_class($object);
        if(!$this->isMappedClass($objectClass)) {
            throw new \RuntimeException(sprintf("%s class was not mapped, cannot use for linking.", $objectClass));
        }

        $this->doLinkObject($document, $object);
    }

    public function createPreLinkedDocument($object)
    {
        $objectClass = get_class($object);
        if(!$this->isMappedClass($objectClass)) {
            throw new \RuntimeException(sprintf("%s class was not mapped, cannot use for linking.", $objectClass));
        }

        $document = $this->createDocument();
        $link = new Link();
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

    public function getRepository()
    {
        return $this->repository;
    }

    protected function doLinkObject($document, $object)
    {
        $link = new Link();
        $link->setClass(get_class($object))
            ->setObjectId($object->getId());
        $document->addLink($link);

        $this->doSaveDocument($document);
    }

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

        if(!in_array($class, $classNames)) {
            return false;
        }

        return true;
    }
    public function getClass()
    {
        return $this->class;
    }
}