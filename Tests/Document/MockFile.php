<?php 
namespace MDB\DocumentBundle\Tests\Document;

class MockFile 
{
    protected $id;
 
    protected $file;
 
    protected $filename;
 
    protected $mimeType;
 
    protected $uploadDate;
 
    protected $length;
 
    protected $chunkSize;
 
    protected $md5;

    protected $changeMessage;

    protected $version;

    protected $format;


    public function getId()
    {
        return $this->id;
    }


    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }


    public function getMimeType()
    {
        return $this->mimeType;
    }

 
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
        return $this;
    }


    public function getUploadDate()
    {
        return $this->uploadDate;
    }


    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }


    public function getLength()
    {
        return $this->length;
    }

    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
        return $this;
    }


    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    public function setMd5($md5)
    {
        $this->md5 = $md5;
        return $this;
    }


    public function getMd5()
    {
        return $this->md5;
    }

    public function setChangeMessage($changeMessage)
    {
        $this->changeMessage = $changeMessage;
        return $this;
    }

    public function getChangeMessage()
    {
        return $this->changeMessage;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }


    public function getVersion()
    {
        return $this->version;
    }
}

