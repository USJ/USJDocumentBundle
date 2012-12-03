<?php

namespace MDB\DocumentBundle\Factory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAware;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;


/**
 * Factory for producing file response from data base
 * 
 */
class FileResponseFactory extends ContainerAware {

	public function createResponse($file, $download = false, $format = 'original')
	{
        $response = new Response();
        $response->headers->set('Content-Type', $file->getMimeType());

        $response = $download? $this->createDownloadable($response, $file) : $response;
        $bytes = ($format == 'original' || is_null($format)) ? $file->getFile()->getBytes() : $this->resize($file, $format);
        $response->setContent($bytes);
        return $response;
	}

    protected function createDownloadable($response, $file) {
        $response->headers->set('Content-Disposition',' attachment; filename="'.$file->getFilename().'"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', $file->getLength());

        return $response;
    }

    protected function resize($file, $format) {
        $filename = $file->getMd5();
        $fs = $this->filesystem($format);
  
        if($fs->has($filename)) {
            return $fs->read($filename);
        }

        $orig_fs = $this->filesystem($orig);

        if(!$orig_fs->has($filename)){
            $orig_fs->write($filename, $file->getFile()->getBytes());
        }
        // convert it to other format.
        
    }


    protected function filesystem($format) {
        $adaptor =  new LocalAdapter('/tmp/'.$format, true);
        return new Filesystem($adaptor);
    }

}