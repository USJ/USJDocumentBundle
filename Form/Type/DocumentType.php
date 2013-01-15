<?php 
namespace MDB\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
	Symfony\Component\Form\FormBuilderInterface,
	MDB\AssetBundle\Form\DataTransformer\NameToAssetTransformer,
	Symfony\Component\OptionsResolver\OptionsResolverInterface,
	Doctrine\Common\Persistence\ObjectManager;

use MDB\DocumentBundle\Form\Type\FileType;

class DocumentType extends AbstractType
{
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
    }

    public function getName()
    {
    	return 'document';
    }
}