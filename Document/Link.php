<?php 
namespace MDB\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/** 
 * This store the link to other object model.
 * @MongoDB\EmbeddedDocument
 */
class Link
{
    /** 
     * @MongoDB\Id 
     */
    protected $id;

    /** 
     * @Assert\NotNull
     * @MongoDB\String
     */
    protected $class;

    /** 
     * @Assert\NotNull
     * @MongoDB\ObjectId 
     */
    protected $objectId;

    public function __construct($class = null, $objectId = null)
    {
        if(!is_null($class)) $this->setClass($class);
        if(!is_null($objectId)) $this->setObjectId($objectId);
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
     * Set className
     *
     * @param string $className
     * @return \Belonging
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Get className
     *
     * @return string $className
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set objectId
     *
     * @param string|MongoId $objectId
     * @return \Belonging
     */
    public function setObjectId($objectId)
    {
        if(!$objectId instanceof \MongoId) {
            $objectId = new \MongoId($objectId);
        }

        $this->objectId = $objectId;
        return $this;
    }

    /**
     * Get objectId
     *
     * @return object_id $objectId
     */
    public function getObjectId()
    {
        return $this->objectId;
    }
}
