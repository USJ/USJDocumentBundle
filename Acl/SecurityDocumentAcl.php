<?php

namespace MDB\DocumentBundle\Acl;

use MDB\DocumentBundle\Model\DocumentInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Implements ACL checking using the Symfony2 Security component
 *
 * @author Marco Leong <leong.chou.kin@usj.edu.mo>
 */
class SecurityDocumentAcl implements DocumentAclInterface
{
    /**
     * Used to retrieve ObjectIdentity instances for objects.
     *
     * @var ObjectIdentityRetrievalStrategyInterface
     */
    protected $objectRetrieval;

    /**
     * The AclProvider.
     *
     * @var MutableAclProviderInterface
     */
    protected $aclProvider;

    /**
     * The current Security Context.
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * The FQCN of the Document object.
     *
     * @var string
     */
    protected $documentClass;

    /**
     * The Class OID for the Comment object.
     *
     * @var ObjectIdentity
     */
    protected $oid;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface                 $securityContext
     * @param ObjectIdentityRetrievalStrategyInterface $objectRetrieval
     * @param MutableAclProviderInterface              $aclProvider
     * @param string                                   $documentClass
     */
    public function __construct(SecurityContextInterface $securityContext,
                                ObjectIdentityRetrievalStrategyInterface $objectRetrieval,
                                MutableAclProviderInterface $aclProvider,
                                $documentClass
    )
    {
        $this->objectRetrieval   = $objectRetrieval;
        $this->aclProvider       = $aclProvider;
        $this->securityContext   = $securityContext;
        $this->documentClass     = $documentClass;
        $this->oid               = new ObjectIdentity('class', $this->documentClass);
    }

    /**
     * Checks if the Security token is allowed to create a new Document.
     *
     * @return boolean
     */
    public function canCreate()
    {
        return $this->securityContext->isGranted('CREATE', $this->oid);
    }

    /**
     * Checks if the Security token is allowed to view the specified Document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canView(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('VIEW', $document);
    }

    /**
     * Checks if the Security token is allowed to edit the specified Document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canEdit(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('EDIT', $document);
    }

    /**
     * Checks if the Security token is allowed to delete the specified Document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canDelete(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('DELETE', $document);
    }

    /**
     * Checks if the user should be able to restore a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canRestore(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('UNDELETE', $document);
    }

    /**
     * Checks if the user should be able to soft delete a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */  
    public function canSoftDelete(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('DELETE', $document);
    }

    /**
     * Checks if the user should be able to hard delete a document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canHardDelete(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('DELETE', $document);
    }

    /**
     * Checks if the user should be able to upload a new version for document.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canAddVersion(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('EDIT', $document);
    }

    /**
     * Checks if the user should be able to restore a document's version.
     *
     * @param  DocumentInterface $document
     * @return boolean
     */
    public function canRestoreVersion(DocumentInterface $document)
    {
        return $this->securityContext->isGranted('EDIT', $document);
    }

    /**
     * Sets the default object Acl entry for the supplied Document.
     *
     * @param  DocumentInterface $document
     * @return void
     */
    public function setDefaultAcl(DocumentInterface $document)
    {
        $objectIdentity = $this->objectRetrieval->getObjectIdentity($document);
        $acl = $this->aclProvider->createAcl($objectIdentity);

        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Installs default Acl entries for the Document class.
     *
     * This needs to be re-run whenever the Document class changes or is subclassed.
     *
     * @return void
     */
    public function installFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->documentClass);

        try {
            $acl = $this->aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $exists) {
            return;
        }

        $this->doInstallFallbackAcl($acl, new MaskBuilder());
        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Installs the default Class Ace entries into the provided $acl object.
     *
     * Override this method in a subclass to change what permissions are defined.
     * Once this method has been overridden you need to run the
     * `mdb:document:installAces --flush` command
     *
     * @param  AclInterface $acl
     * @param  MaskBuilder  $builder
     * @return void
     */
    protected function doInstallFallbackAcl(AclInterface $acl, MaskBuilder $builder)
    {
        $builder->add('iddqd');
        $acl->insertClassAce(new RoleSecurityIdentity('ROLE_SUPER_ADMIN'), $builder->get());

        $builder->reset();
        $builder->add('create');
        $builder->add('view');
        $acl->insertClassAce(new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY'), $builder->get());

        $builder->reset();
        $builder->add('create');
        $builder->add('view');
        $acl->insertClassAce(new RoleSecurityIdentity('ROLE_USER'), $builder->get());
    }

    /**
     * Removes fallback Acl entries for the Document class.
     *
     * This should be run when uninstalling the DocumentBundle, or when
     * the Class Acl entry end up corrupted.
     *
     * @return void
     */
    public function uninstallFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->documentClass);
        $this->aclProvider->deleteAcl($oid);
    }
}