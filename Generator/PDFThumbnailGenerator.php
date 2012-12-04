<?php 

namespace MDB\DocumentBundle\Generator;

use MDB\DocumentBundle\Model\FileInterface;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Generator to generate thumbnails
 */
class PDFThumbnailGenerator extends ContainerAware
{
	public $file;
    public $tmpPath;
    public $outputPath;

	public function generate(FileInterface $file, $bytes = false)
	{
        if($file->getMimeType() != 'application/pdf') {
            throw new \RuntimeException('MIME type not match');
        }

        $logger = $this->container->get('logger');

        $this->file = $file;
        // write to tmp folder, run the cmd with input and output param, then return the bytes.
		$this->tmpPath = $this->saveToDisk($file->getFile()->getBytes());
        $this->outputPath = '/tmp/mdbdocument/converted/'.$this->getMd5Filename();
        $this->createDirectory('/tmp/mdbdocument/converted/');

        $process = $this->buildProcess();
        $process->setTimeout(3600);

        $logger->info('Run command: '. $process->getCommandLine());
        $process->run();

        if(!$process->isSuccessful()){
            throw new \RuntimeException('Error on thumbnail generation: '.$process->getErrorOutput());
        }

        $logger->info('Done.');

        return ($bytes)? file_get_contents($this->outputPath): $this->outputPath;
	}

    public function buildProcess()
    {
        $convert_bin = $this->container->getParameter('mdb_document.imagemagick.convert.bin');
        $fileTmpPath = $this->tmpPath.'[0]';
        $limitMemoryArg = '-limit memory 10M';
        $resizeArg = '-resize 30%x30%';
        $fileOutputPath = $this->outputPath;
        
        $arguments = array();
        $arguments[] = $convert_bin;
        $arguments[] = $limitMemoryArg;

        $arguments[] = "'".$fileTmpPath."'";
        $arguments[] = $resizeArg;
        $arguments[] = "'".$this->outputPath."'";
        // '/usr/bin/convert' -limit memory 10M '/tmp/mdbdocument/md5sum-mockfilename[0]' -resize 30%x30% '/tmp/mdbdocument/converted/md5sum-mockfilename'
        $proc = new Process(implode(' ',$arguments));

        return $proc;
    }

    private function saveToDisk($bytes)
    {
        $dir_path = '/tmp/mdbdocument';
        $fs = $this->getLocalFilesystem($dir_path, true);
        $filename = $this->getMd5Filename();
        $fs->write($filename, $bytes);
        return $dir_path.DIRECTORY_SEPARATOR.$filename;
    }

    private function getMd5Filename()
    {
        return $this->file->getMd5().'-'.$this->file->getFilename();
    }

    private function getFilename()
    {
        return $this->file->getFilename();
    }

    private function createDirectory($path) 
    {
        if(!file_exists($path)){
            mkdir($path);
        }
    }

    private function getLocalFilesystem($dir_path, $create = true)
    {
        return new Filesystem(new LocalAdapter($dir_path, $create));
    }
}