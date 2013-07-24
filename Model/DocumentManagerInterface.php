<?php 

namespace MDB\DocumentBundle\Model;

/**
 * Interface to be implemented by document manager
 *
 * @author Marco Leong <leong.chou.kin@usj.edu.mo>
 */
interface DocumentManagerInterface
{
    /**
     * Persist document to database
     *
     * @param DocumentInterface
     */
    public function saveDocument(DocumentInterface $document);

    /**
     * Retrieve all the document records from database
     *
     * @return collection of DocumentInterface
     */
    public function findAllDocuments();


    /**
     * Query with criteria and return a DocumentInterface from database
     *
     * @param array
     * @return DocumentInterface
     */
    public function findDocumentBy(array $criteria);

    /**
     * find document with given id 
     *
     * @param string $id
     * @return DocumentInterface
     */
    public function findDocumentById($id);
    
    /**
     * find document with given criteria 
     *
     * @param array $criteria
     * @return DocumentInterface
     */
    public function findDocumentsBy(array $criteria);

    /**
     * return document class
     * 
     * @return string
     */
    public function getClass();

}