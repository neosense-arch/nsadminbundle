<?php

namespace NS\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FileSelectType
 *
 * @package NS\AdminBundle\Form\Type
 */
class FileSelectType extends AbstractType
{
    /**
     * @return string
     */
    public function getParent()
	{
		return 'hidden';
	}

    /**
     * @return string
     */
    public function getName()
	{
		return 'ns_file_select';
	}
}
