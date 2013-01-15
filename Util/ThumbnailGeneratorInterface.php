<?php 
namespace MDB\DocumentBundle\Util;

use Imagine\Image\ImagineInterface;

interface ThumbnailGeneratorInterface
{
    /**
     * Function for generating thumnails
     * 
     * @param Imagine $input the source of pdf.
     * @param array $options options that can specify during generation
     * 
     * @return mixed $thumb generated thumbnail
     */
    public function generate(ImagineInterface $input, $options);
} 