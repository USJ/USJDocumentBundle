<?php
namespace MDB\DocumentBundle\Document;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** 
 * @MongoDB\Document(repositoryClass="MDB\DocumentBundle\Repository\DocumentRepository") 
 */
class Document
{
	/** 
     * @MongoDB\Id
     */
	protected $id;

	/** 
     * @MongoDB\ReferenceMany(targetDocument="File",cascade={"all"}, mappedBy="document")  
     */
	protected $files = array();

    /** 
     * @MongoDB\String 
     */
    protected $description;

    /** 
     * @MongoDB\String 
     */
    protected $title;

    /** 
     * @MongoDB\Timestamp 
     * @Gedmo\Timestampable(on="create")
     */
    protected $createAt;

    /**
     * @MongoDB\Field(type="timestamp")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @Gedmo\Blameable(on="create")
     * @MongoDB\String
     */
    protected $createdBy;

    /**
     * @MongoDB\String
     * @Gedmo\Blameable(on="update")
     */
    protected $updatedBy;

    /** 
     * @MongoDB\EmbedMany(targetDocument="Link") 
     */
    protected $links = array();

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
     * Set description
     *
     * @param string $description
     * @return Document
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Add files
     *
     * @param MDB\DocumentBundle\Document\File $files
     */
    public function addFiles(\MDB\DocumentBundle\Document\File $file)
    {
        $this->files[] = $file;
    }

    public function addUploadedFile($upload)
    {
        $file = new File();
        $file->setFile($upload->getPathname());
        $file->setFilename($upload->getClientOriginalName());
        $file->setMimeType($upload->getClientMimeType());

        $this->addFiles($file);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * retrieve the latest version of raw file
     */
    public function getRawFile()
    {
        $file = end($this->files);
        return $file->getFile()->getBytes();
    }

    public function getFile()
    {
        return end($this->files);
    }

    public function getEncodedFile()
    {
        return $this->getFile()->getEncodedFile();
    }

    public function getLatestVersionNumber()
    {
        return count($this->files);
    }

    /**
     * Set createAt
     *
     * @param timestamp $createAt
     * @return \Document
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;
        return $this;
    }

    /**
     * Get createAt
     *
     * @return timestamp $createAt
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }


    /**
     * Add links
     *
     * @param MDB\DocumentBundle\Document\Link $links
     */
    public function addLinks(\MDB\DocumentBundle\Document\Link $links)
    {
        $this->links[] = $links;
    }

    /**
     * Get links
     *
     * @return Doctrine\Common\Collections\Collection $links
     */
    public function getLinks()
    {
        return $this->links;
    }

    

}
