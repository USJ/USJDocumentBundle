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

    public function findDocuments()
    {
        return $this->repository->findAll();
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function doSaveDocument($document)
    {
        $this->dm->persist($document);
        $this->dm->flush();
    }
    
    public function getClass()
    {
        return $this->class;
    }
}