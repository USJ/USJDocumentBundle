<?php
namespace MDB\DocumentBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
*
*/
class StringToTagsTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  array, of tags
     * @return string
     */
    public function transform($array)
    {
        if(is_null($array)) {
            return '';
        }
        return implode(',', $array);
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  string
     * @return array
     */
    public function reverseTransform($string)
    {
        if(is_null($string)) {
            return array();
        }
        $arr = explode(',', $string);
        return ($arr)? $arr: array();
    }
}
