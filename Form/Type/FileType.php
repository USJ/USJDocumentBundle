<?php 
namespace MDB\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
	Symfony\Component\Form\FormBuilderInterface,
	MDB\AssetBundle\Form\DataTransformer\NameToAssetTransformer,
	Symfony\Component\OptionsResolver\OptionsResolverInterface,
	Doctrine\Common\Persistence\ObjectManager;

class FileType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('file','file');		
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' =>'MDB\DocumentBundle\Document\File'
    		)
    	);
    }

    public function getName()
    {
    	return 'file';
    }

}