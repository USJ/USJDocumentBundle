<?php

namespace MDB\DocumentBundle\Event;

use MDB\DocumentBundle\Document\Link;

use MDB\DocumentBundle\Document\Document;
use Symfony\Component\EventDispatcher\Event;

/**
*
*/
class LinkEvent extends Event
{
    protected $link;
    protected $document;

    public function __construct(Document $document, Link $link)
    {
        $this->document = $document;
        $this->link = $link;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function getLink()
    {
        return $this->link;
    }
}
