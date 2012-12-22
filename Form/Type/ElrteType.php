<?php

namespace NS\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ElRTE form field
 *
 */
class ElrteType extends AbstractType
{
	public function getParent()
	{
		return 'textarea';
	}

	public function getName()
	{
		return 'elrte';
	}
}
