<?php
namespace MDB\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    protected $documentClass;

    public function __construct($documentClass)
    {
        $this->documentClass = $documentClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');

        $builder->add('description', 'textarea',array('required' => false));

        // $builder->add('file', 'file',array(
        //     'property_path' => false
        // ));
        $transformer = new \MDB\DocumentBundle\Form\DataTransformer\StringToTagsTransformer();
        $builder->add(
            $builder->create('tags', 'hidden', array('required' => false))
                ->addModelTransformer($transformer)
            );

        $builder->add('files', 'collection', array(
                'by_reference' => false, // set false to use adder/remover instead
                'type' => 'mdb_document_file',
                'allow_add' => true,
                'allow_delete' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->documentClass,
        ));
    }

    public function getName()
    {
        return 'mdb_document_document';
    }
}
