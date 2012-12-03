<?php 
namespace MDB\DocumentBundle\Test\Generator;

use MDB\DocumentBundle\Generator\PdfThumbnailGenerator;
use MDB\DocumentBundle\Tests\Document\MockFile;

class PdfThumbnailGeneratorTest extends \PHPUnit_Framework_TestCase
{
	public function testGenerate()
	{
		$pdfTG = new PdfThumbnailGenerator();
		$result = $pdfTG->generate($this->getMockFile(), true);

		$this->assertEquals(file_get_contents(__PATH__.'/testpdf.png'), $result);
	}

	private function getMockFile() 
	{
		$mf = new MockFile();
		$mf->setFile($this->getGridFSFileWithBytes());
		return $mf;
	}

	private function getGridFSFileWithBytes()
	{
		$gfs = new Doctrine\MongoDB\GridFSFile();
		$bytes = file_get_contents(__PATH__.'/testpdf.pdf');
		$gfs->setBytes($bytes);
		$gfs->isDirty(false);
	}
}