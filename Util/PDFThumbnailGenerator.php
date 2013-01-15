<?php 
namespace MDB\DocumentBundle\Util;

use Imagine\Image\ImageInterface;

/**
 * This class response for PDF Thumbnail generation.
 */
class PDFThumbnailGenerator implements ThumbnailGeneratorInterface
{
    protected $default = array(
        'output_type' => 'path'
    );
    /**
     * {@inheritdoc}
     */
    public function generate(ImageInterface $input, $options)
    {
        $options = array_merge($default, $options);

        $result;
        switch($options['output_type']) {
            case 'path':
                if(!isset($options['output_path'])) {
                    throw new \RuntimeException("You cannot have output_type: path without path specified.");
                } 
                if(isset($options['resize'])) {
                    $input->thumbnail(new Box(320,240));
                }

                $input->setImagick($input->getImagick()->setIteratorIndex(0));
                $input->save($options['path']);
                $result = $options['path'];
                break;
        }
        return $result
    }
}