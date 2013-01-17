<?php 
namespace MDB\DocumentBundle\Form\Type;
use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Doctrine\Common\Persistence\ObjectManager;

/**
 * 
 */
class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('objectId', 'hidden');
        $builder->add('class', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'MDB\DocumentBundle\Document\Link'
            )
        );
    }

    public function getName()
    {
        return 'mdb_document_document_link';
    }

}