<?php

namespace MDB\DocumentBundle\Acl;

use MDB\DocumentBundle\Model\DocumentInterface;

/**
 * Used for checking if the ACL system will allow specific actions
 * to occur.
 *
 * @author Marco Leong <leong.chou.kin@usj.edu.mo>
 */
interface DocumentAclInterface
{
    /**
     * Checks if the user should be able to create a document.
     *
     * @return boolean
     */
    public function canCreate();

    /**
     * Checks if the user should be able to view a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canView(DocumentInterface $document);

    /**
     * Checks if the user should be able to edit a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canEdit(DocumentInterface $document);

    /**
     * Checks if the user should be able to delete a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canDelete(DocumentInterface $document);

    /**
     * Checks if the user should be able to restore a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canRestore(DocumentInterface $document);

    /**
     * Checks if the user should be able to soft delete a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */  
    public function canSoftDelete(DocumentInterface $document);

    /**
     * Checks if the user should be able to hard delete a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canHardDelete(DocumentInterface $document);

    /**
     * Checks if the user should be able to upload a new version for document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canAddVersion(DocumentInterface $document);

    /**
     * Checks if the user should be able to restore a document's version.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canRestoreVersion(DocumentInterface $document);

    /**
     * Sets the default Acl permissions on a document.
     *
     * Note: this does not remove any existing Acl and should only
     * be called on new DocumentInterface instances.
     *
     * @param  DocumentInterface $document
     * @return void
     */
    public function setDefaultAcl(DocumentInterface $document);

    /**
     * Installs the Default 'fallback' Acl entries for generic access.
     *
     * @return void
     */
    public function installFallbackAcl();

    /**
     * Removes default Acl entries
     * @return void
     */
    public function uninstallFallbackAcl();
}