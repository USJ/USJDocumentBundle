<?php 
namespace MDB\DocumentBundle\Converter;

use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;


/**
 * Class to convert PDF file
 * if(bytes) -> save it to file -> CLI convert -> if(bytes) return bytes : path.
 */
class PdfFileConverter implements FileConverterInterface
{
    private $original_path;
    private $output_path;
    /**
     * set file need to be convert
     */
    public function set($data, $bytes = false){
        if($bytes) {
            $fs = $this->filesystem();
            $filename = $this->randFilename();
            $fs->write($filename, $data);
            $this->original_path = $this->tmpPath().'/'. $filename;
        }else{
            $this->$original_path = $data;
        }
    }

    /**
     * run process with arguments
     * $args['format'] is format you want to return
     */
    public function convert($args){
        $output_name = $this->randFilename();
        $output_path = $this->tmpPath($args['format']).'/'.$output_name;
        // convert -cache 20 foo.pdf[0] -resize 10%x10% foo-thumb.png
        $process = $this->buildProcess('/tmp/');
        $process->setTimeout(3600);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
        $md5 = $this->md5($output_path);
        $final_path = $this->tmpPath($args['format']).'/'.$md5;
        rename($output_path, $final_path);
        $this->output_path = $final_path;
    }

    /**
     * This method should generate some things like this,
     * convert -cache 20 foo.pdf[0] -resize 10%x10% foo-thumb.png
     */
    public function buildProcess($output_path, $options) {
        $defaults = array(
            'cache_size' => '20'
        );
        $cmd_opts = array_merge($defaults, $options);

        $cmd_opts['output_path'] = $output_path;
        // $bin, $cache_size, $input_path, $page, $resize_to, $output_path
        $convert_binary = $this->container->getParameter('mdb_document.imagemagick.convert.bin');
        $procBuilder = new ProcessBuilder(array($convert_binary));

        $procBuilder->add('-cache')->add($options['cache_size']);

        if(!isset($this->original_path)) {
            throw new RuntimeException('You have to define original path first');
        }
        $procBuilder->add($original_path.'[0]');

        $procBuilder = isset($cmd_opts['resize']) ? $procBuilder->add('-resize')->add($cmd_opts['resize']) : $procBuilder;

        $procBuilder->add($cmd_opts['output_path']);

        return $procBuilder->getProcess();
    }

    /**
     * get out come (path or bytes)
     */
    public function get($bytes = false){
        if($bytes) {
            file_get_contents($this->output_path);
        }else{
            return $this->output_path;
        }
    }

    private function tmpPath($formal = null) {
        $path =  (is_null($format))? '/tmp': '/tmp/'.$format;
        return $path;
    }
    
    private function filesystem($format = null) {
        $adaptor =  (is_null($format))? new LocalAdapter($this->tmpPath(), true): new LocalAdapter($this->tmpPath($format), true);
        return new Filesystem($adaptor);
    }
    private function randFilename(){
        return uniqid(rand(), true);
    }

    private function md5($path) {
        return md5_file($path);
    }
}