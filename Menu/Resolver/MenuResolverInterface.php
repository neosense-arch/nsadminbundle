<?php

namespace NS\AdminBundle\Menu\Resolver;

use Knp\Menu\ItemInterface;

interface MenuResolverInterface
{
	/**
	 * @param ItemInterface $menu
	 * @return void
	 */
	public function resolve(ItemInterface $menu);
}
