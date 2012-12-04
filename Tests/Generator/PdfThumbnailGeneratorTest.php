<?php 
namespace MDB\DocumentBundle\Test\Generator;

use MDB\DocumentBundle\Generator\PdfThumbnailGenerator;
use MDB\DocumentBundle\Tests\Document\MockFile;

class PdfThumbnailGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->removeIfExist('/tmp/mdbdocument/md5sum-mockfilename');
        $this->removeIfExist('/tmp/mdbdocument/converted/md5sum-mockfilename');
        $this->removeIfExist('/tmp/mdbdocument/converted');
        $this->removeIfExist('/tmp/mdbdocument');
    }

	public function testGenerate()
	{
		$pdfTG = new PdfThumbnailGenerator();
        $pdfTG->setContainer($this->getMockContainer());
		// test as return bytes
		$result = $pdfTG->generate($this->getMockFile(), true);
        
        $this->assertEquals('/tmp/mdbdocument/converted/md5sum-mockfilename',$pdfTG->outputPath);
		$this->assertTrue(file_exists('/tmp/mdbdocument/converted/md5sum-mockfilename'));
	}

	private function getMockFile() 
	{
		$mf = new MockFile();
        $mf->setMd5('md5sum');
        $mf->setFilename('mockfilename');
		$mf->setFile($this->getMockGridFSFileWithBytes());
		return $mf;
	}

    private function getMockContainer()
    {
        $cont = $this->getMock('Symfony\Component\DependencyInjection\Container', array('getParameter', 'get'));
        $cont->expects($this->any())
            ->method('getParameter')
            ->with($this->equalTo('mdb_document.imagemagick.convert.bin'))
            ->will($this->returnValue('/usr/bin/convert'));

        $cont->expects($this->any())
            ->method('get')
            ->with($this->equalTo('logger'))
            ->will($this->returnValue($this->getMockLogger()));
        return $cont;
    }

    private function getMockLogger()
    {
        $logger = $this->getMock('Monolog\Logger', array('info'));

        $logger->expects($this->at(0))->method('info')
            ->with($this->equalTo("Run command: /usr/bin/convert -limit memory 10M '/tmp/mdbdocument/md5sum-mockfilename[0]' -resize 30%x30% '/tmp/mdbdocument/converted/md5sum-mockfilename'"));

        $logger->expects($this->at(1))->method('info')
            ->with($this->equalTo("Done."));

        return $logger;
    }

	private function getMockGridFSFileWithBytes()
	{
        $gfs = $this->getMock('Doctrine\MongoDB\GridFSFile', array('getBytes'));
		$bytes = file_get_contents(__DIR__.'/testpdf.pdf');
		
		$gfs->expects($this->any())
			->method('getBytes')
			->will($this->returnValue($bytes));
		return $gfs;
	}

    private function removeIfExist($path) {
        if(file_exists($path)){
            if(is_dir($path)) {
                rmdir($path);
            }else{
                unlink($path);
            }
        }
    }

}