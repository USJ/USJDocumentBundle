<?php 
namespace MDB\DocumentBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;

use MDB\DocumentBundle\Form\DataTransformer\MongoIdToStringTransformer;
/**
 * 
 */
class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // parent::buildForm($builder, $options);
        $builder->add(
                $builder->create('objectId', 'hidden')
                    ->addViewTransformer(new MongoIdToStringTransformer())
            );
        
        $builder->add('class', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
                'data_class' =>'MDB\DocumentBundle\Document\Link'
            )
        );
    }

    public function getName()
    {
        return 'mdb_document_link';
    }

}