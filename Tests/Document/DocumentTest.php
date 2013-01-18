<?php 
namespace MDB\DocumentBundle\Tests\Document;

use MDB\DocumentBundle\Document\Document;
use MDB\DocumentBundle\Document\Link;

/**
* 
*/
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    
    public function testRemoveLink()
    {
        $document = new Document();
        $link_1 = new Link('ele1-class','ele1-id');
        $link_2 = new Link('ele2-class','ele2-id');
        $link_3 = new Link('ele3-class','ele3-id');

        $document->addLink($link_1);
        $document->addLink($link_2);
        $document->addLink($link_3);

        $document->removeLink($link_2);

        $resultDocument = new Document();
        $resultDocument->addLink($link_1);
        $resultDocument->addLink($link_3);

        // $this->assertEquals($document, $resultDocument);
    }

}