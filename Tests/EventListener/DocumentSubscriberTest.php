<?php
namespace MDB\DocumentBundle\Tests\EventListener;

use MDB\DocumentBundle\EventListener\DocumentSubscriber;
use MDB\DocumentBundle\Event\DocumentEvent;

/**
*
*/
class DocumentSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testVerioningIncrementWithNull()
    {
        $this->markTestSkipped('must be revisited.');

        $document = new MockDocument();
        $file = new MockFile();
        $document->addFile($file);

        $documentSubscriber = new DocumentSubscriber();
        $documentSubscriber->setFileVersion(new DocumentEvent($document));

        $this->assertEquals($file->getVersion(), 1);
    }

    public function testVersioningWithPresettedVersion()
    {
        $this->markTestSkipped('must be revisited.');

        $document = new MockDocument();
        $file = new MockFile();
        $file->setVersion(2);
        $newFile = new MockFile();

        $document->addFile($file);
        $document->addFile($newFile);

        $documentSubscriber = new DocumentSubscriber();
        $documentSubscriber->setFileVersion(new DocumentEvent($document));

        $this->assertEquals($newFile->getVersion(), 3);
    }

    public function testVersioningWithMultipleFiles()
    {
        $this->markTestSkipped('must be revisited.');

        $document = new MockDocument();
        $file1 = new MockFile();
        $file1->setVersion(1);
        $newFile = new MockFile();
        $file2 = new MockFile();
        $file2->setVersion(3);

        $document->addFile($file1);
        $document->addFile($file2);
        $document->addFile($newFile);

        $documentSubscriber = new DocumentSubscriber();
        $documentSubscriber->setFileVersion(new DocumentEvent($document));

        $this->assertEquals($newFile->getVersion(), 4);
    }

}

class MockFile
{
    protected $version;

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }
}

class MockDocument
{
    protected $files = array();

    public function getFiles()
    {
        return $this->files;
    }

    public function addFile($file)
    {
        $this->files[] = $file;

        return $this;
    }
}
