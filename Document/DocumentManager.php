<?php
namespace MDB\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager as ODMDocumentManager ;

/**
* Class act as a service to access documents
*/
class DocumentManager
{	
    /** @var ODM Document Manager */
	protected $dm;
	/** @var class  */
	protected $class;

	public function __construct(ODMDocumentManager $dm, $class)
	{
		$this->dm = $dm;
		$this->class = $class;
	}

    /**
     * @return Document $document object
     */ 
    public function createDocument(){
        $document = new $this->class;
        return $document;
    }

    public function findDocuments()
    {
        return $this->dm->getRepository($this->class)->findAll();
    }

    public function getRepository()
    {
        return $dm->getRepository($this->class);
    }
}