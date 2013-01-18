<?php 
namespace MDB\DocumentBundle\Event;

use Symfony\Component\EventDispatcher\Event;
/**
* 
*/
class DocumentEvent extends Event
{
    protected $document;

    public function __construct($document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }
}