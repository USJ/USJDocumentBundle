<?php 

namespace MDB\DocumentBundle\Converter;
/**
 * Common method for all file converter, basically is CLI convert
 */
interface FileConverterInterface
{
    /**
     * set file need to be convert
     */
    public function set($data, $bytes = false);

    /**
     * run process with arguments
     */
    public function convert($args);

    /**
     * get out come (path or bytes)
     */
    public function get($bytes = false);
}