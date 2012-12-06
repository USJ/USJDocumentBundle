<?php 
namespace MDB\DocumentBundle\Search;

use FOQ\ElasticaBundle\Provider\ProviderInterface;
use Elastica_Type;

class DocumentProvider
{
    protected $documentType;
    protected $documentManager;

    public function __construct(Elastica_Type $documentType, $documentManager)
    {
        $this->documentType = $documentType;
    }

    /**
     * Insert the repository objects in the type index
     *
     * @param Closure $loggerClosure
     */
    public function populate(\Closure $loggerClosure = null)
    {
        if ($loggerClosure) {
            $loggerClosure('Indexing documents');
        }

        $document = new \Elastica_Document();

        // how it maps?
        $this->documentType->addDocuments(array($document));
    }
}