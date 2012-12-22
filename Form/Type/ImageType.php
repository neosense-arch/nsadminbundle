<?php

namespace NS\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Image form field
 *
 */
class ImageType extends AbstractType
{
	/**
	 * Field name
	 * @var string
	 */
	private $name;

	/**
	 * Constructor
	 *
	 * @param $name
	 */
	function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * Retrieves parent type
	 *
	 * @return string
	 */
	public function getParent()
	{
		return 'hidden';
	}

	/**
	 * Retrieves name
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'ns_image';
	}

	/**
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// field name
		$name = $this->name;

		// adding hidden field
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($builder, $name, $options) {
			$form = $event->getForm()->getParent();

			// if image is not required adding delete field
			if (!$options['required']) {
				$form->add($builder->getFormFactory()->createNamed($name . 'Delete', 'hidden'));
			}

			$form->add($builder->getFormFactory()->createNamed($name . 'File', 'file', null, array(
				'label'    => $options['label'],
				'required' => $options['required'],
			)));
		});
	}
}
