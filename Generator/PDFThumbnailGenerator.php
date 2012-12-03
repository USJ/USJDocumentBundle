<?php 

namespace MDB\DocumentBundle\Generator;

use MDB\DocumentBundle\Document\File;
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

	public function generate(File $file, $bytes = false)
	{
        $this->file = $file;
        // write to tmp folder, run the cmd with input and output param, then return the bytes.
		$this->tmpPath = $this->saveToDisk($file->getFile()->getBytes());
        $this->outputPath = '/tmp/mdbdocument/converted/'.$this->getMd5Filename();

        $process = $this->buildProcess();
        $process->setTimeout(3600);
        $process->run();

        if(!$process->isSuccessful()){
            throw new \RuntimeException('Process returns an error'.$process->getErrorOutput());
        }

        $logger = $this->container->get('logger');
        $logger->info('Ran command: '. $process->getCommandLine());

        return ($bytes)? file_get_contents($this->outputPath): $this->outputPath;
	}

    public function buildProcess()
    {
        $convert_bin = $this->container->getParameter('mdb_document.imagemagick.convert.bin');
        $procBuilder = new ProcessBuilder(array($convert_bin));

        $procBuilder->add(array('-cache 10'));
        $procBuilder->add(array($this->tmpPath.'[0]'));
        $procBuilder->add(array('-resize 30%x30%'));

        $procBuilder->add(array($this->outputPath));
        return $procBuilder->getProcess();
    }

    private function saveToDisk($bytes)
    {
        $dir_path = '/tmp/mdbdocuemnt';
        $fs = $this->getLocalFilesystem($dir_path, true);
        $filename = $this->getMd5Filename();

        $fs->write($filename, $bytes);

        return $dir_path.'/'.$filename;
    }

    private function getMd5Filename(){
        return $this->file->getMd5().'-'.$this->file->getFilename();
    }

    private function getFilename()
    {
        return $this->file->getFilename();
    }

    private function getLocalFilesystem($dir_path, $create = true){
        return new Filesystem(new LocalAdapter($this->tmpPath(), $create));
    }
}