<?php

namespace MDB\DocumentBundle\Factory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAware;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;
use MDB\DocumentBundle\Model\FileInterface;
use MDB\DocumentBundle\Generator\ThumbnailGeneratorManager;

/**
 * Factory for producing file response from data base
 * 
 */
class FileResponseFactory extends ContainerAware {

	public function createResponse($file, $download = false, $format = 'original')
	{
        $response = new Response();
        if($format === 'thumbnail') {
            $response->headers->set('Content-Type', 'image/png');
        }else{
            $response->headers->set('Content-Type', $file->getMimeType());
        }

        $response = $download? $this->createDownloadable($response, $file) : $response;
        $bytes = ($format == 'original' || is_null($format)) ? $file->getBytes() : $this->resize($file, $format);
        $response->setContent($bytes);
        return $response;
	}

    protected function createDownloadable($response, $file) 
    {
        $response->headers->set('Content-Disposition',' attachment; filename="'.$file->getFilename().'"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', $file->getLength());

        return $response;
    }

    protected function resize(FileInterface $file, $format) 
    {
        if($format === 'thumbnail') {
            if($file->getMimeType() === 'application/pdf') {
                $thumbGen = $this->container->get('mdb_document.thumbnail_generator.pdf');
                return $thumbGen->generate($file, true);
            }
        }
    }

    protected function filesystem($format) 
    {
        $adaptor =  new LocalAdapter('/tmp/'.$format, true);
        return new Filesystem($adaptor);
    }

}