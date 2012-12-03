<?php 

namespace MDB\DocumentBundle\Generator;

use MDB\DocumentBundle\Document\File;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Generator to generate thumbnails
 */
class PDFThumbnailGenerator
{
	public $file;
    public $tmpPath;

	public function generate(File $file, $bytes = false)
	{
        $this->file = $file;
        // write to tmp folder, run the cmd with input and output param, then return the bytes.
		$file->getFile()->getBytes();

	}

    public function buildProcess()
    {
        $procBuilder = new ProcessBuilder();

    }

    private function getFilename()
    {
        return $this->file->getFilename();
    }

    private function getLocalFilesystem($dir_path, $create = true){
        return new Filesystem(new LocalAdapter($this->tmpPath(), $create));
    }
}