<?php
namespace MDB\DocumentBundle\Document;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** 
 * @MongoDB\Document(repositoryClass="MDB\DocumentBundle\Repository\DocumentRepository") 
 */
class Document
{
	/** @MongoDB\Id */
	protected $id;

	/** @MongoDB\ReferenceMany(targetDocument="File",cascade={"all"})  */
	protected $files = array();

    /** @MongoDB\String */
    protected $description;

    /** @MongoDB\String */
    protected $title;

    /** @MongoDB\Timestamp */
    protected $createAt;

    public function __construct()
    {
        // $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = isset($this->createdAt) ? $this->createAt : time();
    }
    
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
     * Set current
     *
     * @param MDB\DocumentBundle\Document\File $current
     * @return Document
     */
    public function setCurrent(\MDB\DocumentBundle\Document\File $current)
    {
        $this->current = $current;
        return $this;
    }

    /**
     * Get current
     *
     * @return MDB\DocumentBundle\Document\File $current
     */
    public function getCurrent()
    {
        return $this->current;
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
    public function addFiles(\MDB\DocumentBundle\Document\File $files)
    {
        $this->files[] = $files;
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

    public function getLatestFile()
    {
        return end($this->files);
    }
}
