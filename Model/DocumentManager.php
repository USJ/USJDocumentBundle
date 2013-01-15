<?php 
namespace MDB\DocumentBundle\Model;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use MDB\DocumentBundle\Event\DocumentEvent;
use MDB\DocumentBundle\Events;

abstract class DocumentManager 
{
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function saveDocument($document) 
    {
        $event = new DocumentEvent($document);
        $this->dispatcher(Events::DOCUMENT_PRE_PERSIST, $event);

        $this->doSaveDocument($document);

        $event = new DocumentEvent($document);
        $this->dispatcher(Events::DOCUMENT_POST_PERSIST, $event);
    }

    public function findAllWorkOrders()
    {
        return $this->repository->findAll();
    }

    public function findWorkOrderBy($criteria)
    {
        return $this->repository->findBy($criteria);
    }

    public function findWorkOrderById($id)
    {
        return $this->repository->findOneById($id);
    }

    public function findWorkOrdersBy($criteria)
    {
        return $this->repository->findBy($criteria);
    }

}