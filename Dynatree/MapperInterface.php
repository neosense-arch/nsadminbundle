<?php
namespace NS\AdminBundle\Dynatree;

interface MapperInterface
{
	/**
	 * @param  mixed $rootObject
	 * @return DynatreeNode
	 */
	public function getDynatreeNode($rootObject);
}