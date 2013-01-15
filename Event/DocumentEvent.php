<?php 
namespace MDB\DocumentBundle\Event;
/**
* 
*/
class DocumentEvent
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