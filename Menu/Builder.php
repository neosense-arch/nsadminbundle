<?php

namespace NS\AdminBundle\Menu;

use NS\AdminBundle\Menu\Resolver\MenuResolverCollection;
use NS\AdminBundle\Menu\Resolver\MenuResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\VoterInterface;

/**
 * Admin panel menu builder
 *
 */
class Builder
{
	/**
	 * @var FactoryInterface
	 */
	private $factory;

	/**
	 * @var Matcher
	 */
	private $matcher;

	/**
	 * @var MenuResolverCollection
	 */
	private $resolvers;

	/**
	 * Constructor
	 *
	 * @param  FactoryInterface       $factory
	 * @param  Matcher                $matcher
	 * @param  MenuResolverCollection $resolvers
	 * @return Builder
	 */
	public function __construct(FactoryInterface $factory, Matcher $matcher, MenuResolverCollection $resolvers)
	{
		$this->factory   = $factory;
		$this->matcher   = $matcher;
		$this->resolvers = $resolvers;
	}

    /**
     * Creates main menu
     *
     * @param VoterInterface $voter
     * @throws \Exception
     * @return ItemInterface
     */
	public function createMainMenu(VoterInterface $voter)
	{
		// adding route voter to menu matcher
		$this->matcher->addVoter($voter);

		// root node
		$menu = $this->factory
			->createItem('root')
			->setChildrenAttribute('class', 'nav');

		// forming menu
		/** @var MenuResolverInterface $resolver */
		foreach ($this->resolvers as $resolver) {
			$resolver->resolve($menu);
		}

        // parent option
        /** @var ItemInterface $node */
        foreach ($menu->getChildren() as $node) {
            $parentName = $node->getExtra('parent');
            if ($parentName) {
                $parent = $menu->getChild($parentName);
                if (!$parent) {
                    throw new \Exception("Menu item named '{$parentName}' wasn't found");
                }
                $menu->removeChild($node);
                $parent->addChild($node);
            }
        }

        // reordering
        $positions = array();
        /** @var ItemInterface $node */
        foreach ($menu->getChildren() as $node) {
            $positions[$node->getName()] = $node->getExtra('position', 50);
        }
        array_multisort($positions, SORT_ASC, array_values($positions));
        $menu->reorderChildren(array_keys($positions));

		return $menu;
	}

	/**
	 * Creates submenu
	 *
	 * @param  ItemInterface $mainMenu
	 * @return ItemInterface|null
	 */
	public function createSubMenu(ItemInterface $mainMenu)
	{
		/** @var ItemInterface $item */
		foreach ($mainMenu->getChildren() as $item) {
			if ($this->matcher->isCurrent($item) || $this->matcher->isAncestor($item)) {
                $item->setDisplayChildren(true);
				return $item;
			}
		}
		return null;
	}
}
