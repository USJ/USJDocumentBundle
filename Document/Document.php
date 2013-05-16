<?php
namespace MDB\DocumentBundle\Document;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\MappedSuperclass
 */
class Document
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceMany(targetDocument="File",cascade={"all"})
     * @Assert\Count(min="1", minMessage="You must have uploaded one file.")
     */
    protected $files;

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
    protected $createdAt;

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
    protected $links;

    /**
     * @MongoDB\Collection
     */
    protected $tags = array();

    /**
     * @MongoDB\Boolean
     */
    protected $featured;

    public function __construct()
    {
        // using ArrayCollection, is better, the Form can be handle properly
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set createAt
     *
     * @param  timestamp $createAt
     * @return \Document
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * Add links
     *
     * @param MDB\DocumentBundle\Document\Link $links
     */
    public function addLink(\MDB\DocumentBundle\Document\Link $links)
    {
        $this->links->add($links);
    }

    public function removeLink(\MDB\DocumentBundle\Document\Link $linkToRemove)
    {
        $this->links->removeElement($linkToRemove);
    }

    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
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
     * Set updatedAt
     *
     * @param  timestamp $updatedAt
     * @return \Document
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return timestamp $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdBy
     *
     * @param  string    $createdBy
     * @return \Document
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string $createdBy
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param  string    $updatedBy
     * @return \Document
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string $updatedBy
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
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

    public function isFeatured()
    {
        return $this->featured;
    }

    public function setFeatured($bool)
    {
        $this->featured = $bool;

        return $this;
    }

    public function isImage()
    {
        $type = explode('/',$this->files[0]->getMimeType());

        return ($type[0] == 'image') ? true : false;
    }
}
