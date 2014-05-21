<?php

namespace NS\AdminBundle\Form\Type;

/**
 * Class EntityAddType
 *
 * @package NS\AdminBundle\Form\Type
 */
class EntityAddType extends ChoiceAddType
{
	/**
	 * Retrieves parent type
	 *
	 * @return string
	 */
	public function getParent()
	{
		return 'entity';
	}

	/**
	 * Retrieves name
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'ns_entity_add';
	}
}
