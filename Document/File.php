<?php
namespace MDB\DocumentBundle\Document;

use MDB\DocumentBundle\Model\File as BaseFile;

/**
 */
class File extends BaseFile
{

    /**
     * GridFSFile class
     */
    protected $gridFsFile;

    /**
     */
    protected $filename;

    /**
     */
    protected $mimeType;

    /**
     */
    protected $length;

    /**
     */
    protected $chunkSize;

    /**
     */
    protected $md5;

    /**
     */
    protected $changeMessage;

    /**
     */
    protected $version;

    /**
     */
    protected $format;


    public function setFile($file)
    {
        return $this->setGridFsFile($file);
    }

    /**
     * Set file
     *
     * @param  file $file
     * @return File
     */
    public function setGridFsFile($file)
    {
        if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            $this->gridFsFile = $file->getPathname();
            $this->setFilename($file->getClientOriginalName());
            $this->setMimeType($file->getClientMimeType());
        } else {
            $this->gridFsFile = $file;
        }

        return $this;
    }

    public function getGridFsFile()
    {
        return $this->gridFsFile;
    }

    /**
     * Get file
     *
     * @return file $file
     */
    public function getFile()
    {
        return $this->gridFsFile;
    }

    /**
     * Set filename
     *
     * @param  string $filename
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
     * @param  string $mimeType
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
     * Set length
     *
     * @param  int  $length
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
     * @param  int  $chunkSize
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
     * @param  string $md5
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
     * @param  string $changeMessage
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
     * @param  int  $version
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
     * @param  string $format
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

    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set createdAt
     *
     * @param  timestamp $createdAt
     * @return \File
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return timestamp $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set uploadedBy
     *
     * @param  string $uploadedBy
     * @return \File
     */
    public function setUploadedBy($uploadedBy)
    {
        $this->uploadedBy = $uploadedBy;

        return $this;
    }

    /**
     * Get uploadedBy
     *
     * @return string $uploadedBy
     */
    public function getUploadedBy()
    {
        return $this->uploadedBy;
    }
}
