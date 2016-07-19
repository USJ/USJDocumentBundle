<?php
namespace MDB\DocumentBundle\Document;

use MDB\DocumentBundle\Model\DocumentInterface;

/**
 */
abstract class Document implements DocumentInterface
{
    /**
     */
    protected $description;

    /**
     */
    protected $title;

    /**
     */
    protected $tags = array();


    /**
     * Set description
     *
     * @param  string   $description
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
     * @param  string   $title
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
    public function addFile(\MDB\DocumentBundle\Document\File $file)
    {
        $this->files->add($file);
    }

    public function removeFile(\MDB\DocumentBundle\Document\File $file)
    {
        $this->files->removeElement($file);
    }

    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
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

    /**
     * retrieve the latest version of raw file
     */
    public function getRawFile()
    {
        $file = end($this->files);

        return $file->getFile()->getBytes();
    }

    public function getFile($version = null)
    {
        if (!is_null($version)) {
            foreach ($this->files as $file) {
                if ($file->getVersion() == $version ) {
                    return $file;
                }
            }
        }

        return $this->files->last();
    }

    public function getEncodedFile()
    {
        if (end($this->files)) {
            return end($this->files)->getEncodedFile();
        }

        return '';
    }

    public function getLatestVersionNumber()
    {
        return count($this->files);
    }

    /**
     * Get createAt
     *
     * @return timestamp $createAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * Set tags
     *
     * @param  array    $tags
     * @return Document
     */
    public function setTags($tags)
    {
        return $this->tags = $tags;
    }

    /**
     * Get tags
     *
     * @return array $tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function isImage()
    {
        $type = explode('/',$this->files[0]->getMimeType());

        return ($type[0] == 'image') ? true : false;
    }
}
