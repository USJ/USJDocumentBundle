<?php 
namespace MDB\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
	Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\OptionsResolver\OptionsResolverInterface,
	Doctrine\Common\Persistence\ObjectManager;

class FileType extends AbstractType
{
    protected $fileClass;

    public function __construct($fileClass)
    {
        $this->fileClass = $fileClass;
    }
	
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('file','file', array(
                'label_render' => false
            ));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' => $this->fileClass
    		)
    	);
    }

    public function getName()
    {
    	return 'mdb_document_file';
    }

}