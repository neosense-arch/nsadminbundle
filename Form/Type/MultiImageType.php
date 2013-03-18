<?php

namespace NS\AdminBundle\Form\Type;

use NS\AdminBundle\Form\DataTransformer\ArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Image form field
 *
 */
class MultiImageType extends AbstractType
{
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
		return 'ns_multi_image';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}

	/**
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addViewTransformer(new ArrayToStringTransformer());
	}
}
