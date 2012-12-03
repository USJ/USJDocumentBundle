<?php 
namespace MDB\DocumentBundle\Test\Generator;

use MDB\DocumentBundle\Generator\PdfThumbnailGenerator;
use MDB\DocumentBundle\Tests\Document\MockFile;

class PdfThumbnailGeneratorTest extends \PHPUnit_Framework_TestCase
{
	public function testGenerate()
	{
		$pdfTG = new PdfThumbnailGenerator();
		// test as return bytes
		$result = $pdfTG->generate($this->getMockFile(), true);

		$this->assertEquals(file_get_contents(__PATH__.'/testpdf.png'), $result);
	}

	private function getMockFile() 
	{
		$mf = new MockFile();
		$mf->setFile($this->getMockGridFSFileWithBytes());
		return $mf;
	}

	private function getMockGridFSFileWithBytes()
	{
        $gfs = $this->getMock('Doctrine\MongoDB\GridFSFile', array('getBytes'));
		$bytes = file_get_contents(__PATH__.'/testpdf.pdf');
		
		$gfs->expects($this->once())
			->method('getBytes')
			->with($this->returnValue($bytes));
		return $gfs;
	}
}