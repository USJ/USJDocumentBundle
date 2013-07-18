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
    protected $repository;

    public function __construct($dispatcher, $dm, $fileClass)
    {
        $this->fileClass = $fileClass;
        $this->dm = $dm;
        $this->repository = $this->dm->getRepository($fileClass);
    }

    public function createFile($uploadedFile = null)
    {
        $class = $this->fileClass;

        if (!is_null($uploadedFile) && $uploadedFile instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            $file = new $class;
            $file->setGridFsFile($uploadedFile);

            return $file;
        }

        return new $class;
    }

    public function findFileById($id)
    {
        return $this->repository->findOneById($id);
    }

    public function findFileByFilename($filename)
    {
        return $this->repository->findOneByFilename($filename);
    }

    public function isNewFile($file)
    {
        return !$this->dm->getUnitOfWork()->isInIdentityMap($file);
    }

}
