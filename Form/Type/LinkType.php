<?php
namespace MDB\DocumentBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use MDB\DocumentBundle\Form\DataTransformer\MongoIdToStringTransformer;
/**
 *
 */
class LinkType extends AbstractType
{
    protected $linkClass;

    public function __construct($linkClass)
    {
        $this->linkClass = $linkClass;
    }

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
                'data_class' => $this->linkClass
            )
        );
    }

    public function getName()
    {
        return 'mdb_document_link';
    }

}
