<?php 
namespace MDB\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** 
 * This store the link to other object model.
 * @MongoDB\EmbeddedDocument
 */
class Link
{
    /** @MongoDB\Id */
    protected $id;

    /** @MongoDB\String */
    protected $class;

    /** @MongoDB\ObjectId */
    protected $objectId;

    public function __construct($class = null, $objectId = null)
    {
        if(!is_null($class)) $this->class = $class;
        if(!is_null($objectId)) $this->objectId = $objectId;
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
     * @param object_id $objectId
     * @return \Belonging
     */
    public function setObjectId($objectId)
    {
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
