<?php 
namespace MDB\DocumentBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
/**
* 
*/
class MongoIdToStringTransformer implements DataTransformerInterface
{
    public function transform($mongoId)
    {
        if(null === $mongoId) {
            return "";
        }
        return (string) $mongoId;
    }

    public function reverseTransform($string)
    {
        if (!$string) {
            return null;
        }

        return new \MongoId($string);
    }
}