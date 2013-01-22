<?php 
namespace MDB\DocumentBundle\Document;

use MDB\DocumentBundle\Model\FileManager as BaseFileManager;
/**
* 
*/
class FileManager extends BaseFileManager
{
    protected $fileClass;
    protected $dm;

    public function __construct($dispatcher, $dm, $fileClass)
    {
        $this->fileClass = $fileClass;
        $this->dm = $dm;  
    }
    
    public function createFile()
    {
        $class = $this->fileClass;
        return new $class;        
    }

    public function isNewFile($file)
    {
        return !$this->dm->getUnitOfWork()->isInIdentityMap($file);
    }

}