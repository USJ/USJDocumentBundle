<?php
namespace MDB\DocumentBundle\Model;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use MDB\DocumentBundle\Event\DocumentEvent;
use MDB\DocumentBundle\Events;
use MDB\DocumentBundle\Model\DocumentInterface;

abstract class DocumentManager implements DocumentManagerInterface
{
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function saveDocument(DocumentInterface $document)
    {
        $event = new DocumentEvent($document);
        $this->dispatcher->dispatch(Events::DOCUMENT_PRE_PERSIST, $event);

        $this->doSaveDocument($document);

        $event = new DocumentEvent($document);
        $this->dispatcher->dispatch(Events::DOCUMENT_POST_PERSIST, $event);
    }

    public function findAllDocuments()
    {
        return $this->repository->findAll();
    }

    public function findDocumentBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findDocumentById($id)
    {
        return $this->repository->findOneBy(array('_id' => new \MongoId($id)));
    }

    public function findDocumentsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

}
