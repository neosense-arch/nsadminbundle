<?php

namespace NS\AdminBundle\Form\Type;

use NS\AdminBundle\Form\DataTransformer\ArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
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

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'multiple' => true,
		));
	}

	/**
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$multiple = array_key_exists('multiple', $options) ? (bool)$options['multiple'] : true;

		$builder
			->setAttribute('multiple', $multiple);

		if ($multiple) {
			$builder->addViewTransformer(new ArrayToStringTransformer());
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['multiple'] = $options['multiple'];
	}

}
