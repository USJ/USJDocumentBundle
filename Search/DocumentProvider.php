<?php 

namespace MDB\DocumentBundle\Search;

use FOQ\ElasticaBundle\Doctrine\AbstractProvider;
use FOQ\ElasticaBundle\Provider\ProviderInterface;
use Elastica_Document;

class DocumentProvider implements ProviderInterface
{
    protected $documentType;
    protected $managerRegistry;
    protected $objectClass;

    public function __construct($documentType, $objectClass, $managerRegistry)
    {
        $this->documentType = $documentType;
        $this->managerRegistry = $managerRegistry;
        $this->objectClass = $objectClass;
    }

    public function populate(\Closure $loggerClosure = null)
    {
        $queryBuilder = $this->createQueryBuilder();
        $nbObjects = $this->countObjects($queryBuilder);

        for ($offset = 0; $offset < $nbObjects; $offset += 100) {
            if ($loggerClosure) {
                $stepStartTime = microtime(true);
            }
            $objects = $this->fetchSlice($queryBuilder, 100 , $offset);

            $this->documentType->addDocuments($objects);

            if ($loggerClosure) {
                $stepNbObjects = count($objects);
                $stepCount = $stepNbObjects + $offset;
                $percentComplete = 100 * $stepCount / $nbObjects;
                $objectsPerSecond = $stepNbObjects / (microtime(true) - $stepStartTime);
                $loggerClosure(sprintf('%0.1f%% (%d/%d), %d objects/s', $percentComplete, $stepCount, $nbObjects, $objectsPerSecond));
            }
        }
    }

    protected function countObjects($queryBuilder)
    {
        return $queryBuilder->getQuery()->execute()->count();
    }

    protected function fetchSlice($queryBuilder, $limit, $offset)
    {
        $docs = $queryBuilder
            ->skip($offset)
            ->limit($limit)
            ->getQuery()->execute();

        $objects = array();

        foreach($docs as $doc) {

            $document = new Elastica_Document(
                $doc->getId(),
                array(
                    "title" => $doc->getTitle(),
                    "description" => $doc->getDescription(),
                    "attachment" => $doc->getEncodedFile()
                ),
                "document",
                "mdb_document"
             );


            $objects[] = $document;
       }
       return $objects;
    }

    protected function createQueryBuilder()
    {
        return $this->managerRegistry
            ->getRepository($this->objectClass)
            ->createQueryBuilder($this->objectClass);
    }

}