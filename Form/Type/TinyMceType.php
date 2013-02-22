<?php

namespace NS\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TinyMceType extends AbstractType
{
	public function getParent()
	{
		return 'textarea';
	}

	public function getName()
	{
		return 'tinymce';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		parent::setDefaultOptions($resolver);

		$resolver->setDefaults(array(
			'attr' => array(
				'class'      => 'tinymce',
				'data-theme' => 'advanced'
			)
		));
	}
}
