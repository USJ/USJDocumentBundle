<?php 
namespace MDB\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Doctrine\Common\Persistence\ObjectManager;

class PreLinkedDocumentType extends AbstractType
{
    protected $documentClass;  

    public function __construct($documentClass)
    {
        $this->documentClass = $documentClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');

        $builder->add('description', 'textarea');

        $builder->add('files', 'collection', array(
                'by_reference' => false, // set false to use adder/remover instead 
                'type' => 'mdb_document_file',
                'allow_add' => true,
                'allow_delete' => false
            ));

        $builder->add('links', 'collection', array(
                'by_reference' => false, // set false to use adder/remover instead 
                'type' => 'mdb_document_link',
                'allow_add' => true,
                'allow_delete' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->documentClass,
            'cascade_validation' => true
        ));        
    }

    public function getName()
    {
        return 'mdb_document_pre_linked_document';
    }
}