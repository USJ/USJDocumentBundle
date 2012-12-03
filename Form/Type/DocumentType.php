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

		$builder->add('description', 'text');

		// $builder->add('files', 'collection',array(
  //           'type' => new FileType(),
  //           'allow_add' => true,
  //           'by_reference' => false,
  //           'allow_delete' => true, // should render default button, change text with widget_remove_btn
  //           'prototype' => true,
  //           'widget_add_btn' => array('label' => "add file"),
  //           'show_legend' => false, // dont show another legend of subform
  //           'options' => array(
  //               'widget_remove_btn' => array('label' => "remove this", "icon" => "pencil", 'attr' => array('class' => 'btn')),
  //               'widget_control_group' => false
  //           )
  //       ));	
        $builder->add('files', 'file',array(
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