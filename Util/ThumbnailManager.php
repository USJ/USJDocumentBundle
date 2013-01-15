<?php 
namespace MDB\DocumentBundle\Util;

use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use MDB\DocumentBundle\Model\FileInterface;
use Imagine\Imagick\Imagine;
use Symfony\Component\DependencyInjection\ContainerAware;

class ThumbnailManager extends ContainerAware
{
    /**
     * @param mixed $input could be path of file
     */
    public function createThumb(FileInterface $file, $options) 
    {
        $extGuesser = new ExtensionGuesser();
        $ext = $extGuesser->guess($file->getMimeType());

        $className = strtoupper($exp).'ThumbnailGenerator';

        $generator = (class_exists($className)? new $className(): throw new \RuntimeExceptions("Generator class not exists"));
        
        $image = $this->getImage($file->getBytes(), $ext, $false);
        return $generator->generate($image, $options);
    }

    private function getImage($data, $ext, $isPath = true){
        $image = null;
        switch(strtoupper($ext)) {
            case "PDF":
                $imagine = new Imagine();
                $image = $isPath ? $imagine->read($data) : $imagine->load($data);
                break;
        }
        return $image;
    }
} 
