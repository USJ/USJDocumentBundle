<?php 
namespace MDB\DocumentBundle\Document;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MDB\DocumentBundle\Model\File as BaseFile;

/** @MongoDB\Document */
class File extends BaseFile
{
	 /** @MongoDB\Id */
    protected $id;
 
    /** 
     * GridFSFile class
     * @MongoDB\File
     */
    protected $file;
 
    /** @MongoDB\String */
    protected $filename;
 
    /** @MongoDB\String */
    protected $mimeType;
 
    /** @MongoDB\Date */
    protected $uploadDate;
 
    /** @MongoDB\Int */
    protected $length;
 
    /** @MongoDB\Int */
    protected $chunkSize;
 
    /** @MongoDB\String */
    protected $md5;

    /** @MongoDB\String */
    protected $changeMessage;

    /** @MongoDB\Int */
    protected $version;

    /** @MongoDB\String */
    protected $format;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set file
     *
     * @param file $file
     * @return File
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return file $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get filename
     *
     * @return string $filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string $mimeType
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set uploadDate
     *
     * @param date $uploadDate
     * @return File
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
        return $this;
    }

    /**
     * Get uploadDate
     *
     * @return date $uploadDate
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * Set length
     *
     * @param int $length
     * @return File
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * Get length
     *
     * @return int $length
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set chunkSize
     *
     * @param int $chunkSize
     * @return File
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
        return $this;
    }

    /**
     * Get chunkSize
     *
     * @return int $chunkSize
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * Set md5
     *
     * @param string $md5
     * @return File
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
        return $this;
    }

    /**
     * Get md5
     *
     * @return string $md5
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * Set changeMessage
     *
     * @param string $changeMessage
     * @return File
     */
    public function setChangeMessage($changeMessage)
    {
        $this->changeMessage = $changeMessage;
        return $this;
    }

    /**
     * Get changeMessage
     *
     * @return string $changeMessage
     */
    public function getChangeMessage()
    {
        return $this->changeMessage;
    }

    /**
     * Set version
     *
     * @param int $version
     * @return File
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Get version
     *
     * @return int $version
     */
    public function getVersion()
    {
        return $this->version;
    }
    public function getEncodedFile()
    {
        $raw=$this->getFile()->getBytes();
        return base64_encode($raw);
    }

    public function getBytes()
    {
        return $this->getFile()->getBytes();
    }

    /**
     * Set format
     *
     * @param string $format
     * @return \File
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Get format
     *
     * @return string $format
     */
    public function getFormat()
    {
        return $this->format;
    }
}
