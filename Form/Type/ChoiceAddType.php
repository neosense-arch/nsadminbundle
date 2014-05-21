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

class ChoiceAddType extends AbstractType
{
	/**
	 * Retrieves parent type
	 *
	 * @return string
	 */
	public function getParent()
	{
		return 'choice';
	}

	/**
	 * Retrieves name
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'ns_choice_add';
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
            'url'          => null,
            'dialogWidth'  => 400,
            'dialogHeight' => 450,
		));
	}

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @throws \Exception
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        if (!$options['url']) {
            throw new \Exception("Required attribute 'url' wasn't found");
        }
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
        $view->vars['url']          = $options['url'];
        $view->vars['dialogWidth']  = $options['dialogWidth'];
        $view->vars['dialogHeight'] = $options['dialogHeight'];
	}

}
