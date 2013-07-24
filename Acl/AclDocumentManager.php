<?php 

namespace MDB\DocumentBundle\Acl;

use MDB\DocumentBundle\Model\DocumentManagerInterface;

/**
 * Manager to wrap \MDB\DocumentBundle\Document\DocumentManager service 
 * to acl enabled services
 */
class AclDocumentManager
{
	
	protected $realManager;
    protected $documentAcl;

    public function __construct(DocumentManagerInterface $documentManager, $documentAcl)
    {
        $this->realManager = $documentManager;
        $this->documentAcl = $documentAcl;
    }

    public function findDocumentById($id)
    {
        $document = $this->realManager->findDocumentById($id);

        if (!$this->documentAcl->canView($document)) {
            throw new AccessDeniedException();
        }

        return $document;
    }

    public function saveDocument($document)
    {
        if (!$this->documentAcl->canCreate()) {
            throw new AccessDeniedException();
        }

        $newDocument = $this->isNewDocument($document);

        if (!$newDocument && $this->documentAcl->canEdit($document)) {
            throw new AccessDeniedException();
        }

        $this->realManager->saveDocument($document);

        if ($newDocument) {
            $this->documentAcl->setDefaultAcl($document);
        }

    }

    public function findDocumentsBy($criteria)
    {
        $documents = $this->realManager->findDocumentsBy($criteria);
        foreach ($documents as $document) {
            if (!$this->documentAcl->canView($document)) {
                throw new AccessDeniedException();
            }
        }

        return $documents;
    }

    public function isNewDocument($document)
    {
        return $this->realManager->isNewDocument($document);
    }
}