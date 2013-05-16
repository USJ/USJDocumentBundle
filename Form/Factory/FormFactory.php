<?php
namespace MDB\DocumentBundle\Form\Factory;

/**
 *
 */
use Symfony\Component\Form\FormFactoryInterface;

class FormFactory
{
    private $formFactory;
    private $name;
    private $type;
    private $validationGroups;

    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
    }

    public function createForm()
    {
        return $this->formFactory->createNamed($this->name, $this->type, null, array('validation_groups' => $this->validationGroups));
    }
}
