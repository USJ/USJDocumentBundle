<?php 
namespace MDB\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
	Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\OptionsResolver\OptionsResolverInterface,
	Doctrine\Common\Persistence\ObjectManager;

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

		$builder->add('description', 'textarea');

        $builder->add('file', 'file',array(
            'property_path' => false
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