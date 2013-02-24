<?php

namespace NS\AdminBundle\Menu;

use NS\AdminBundle\Menu\Resolver\MenuResolverCollection;
use NS\AdminBundle\Menu\Resolver\MenuResolverInterface;
use NS\AdminBundle\Service\AdminService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\UriVoter;
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
	 * @var AdminService
	 */
	private $adminService;

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
	 * @param  AdminService           $adminService
	 * @param  Matcher                $matcher
	 * @param  MenuResolverCollection $resolvers
	 * @return Builder
	 */
	public function __construct(FactoryInterface $factory, AdminService $adminService, Matcher $matcher, MenuResolverCollection $resolvers)
	{
		$this->factory      = $factory;
		$this->adminService = $adminService;
		$this->matcher      = $matcher;
		$this->resolvers    = $resolvers;
	}

	/**
	 * Creates main menu
	 *
	 * @return ItemInterface
	 */
	public function createMainMenu()
	{
		// adding route voter to menu matcher
		$this->matcher->addVoter($this->createVoter());

		// root node
		$menu = $this->factory
			->createItem('root')
			->setChildrenAttribute('class', 'nav');

		// forming menu
		/** @var MenuResolverInterface $resolver */
		foreach ($this->resolvers as $resolver) {
			$resolver->resolve($menu);
		}

		return $menu;
	}

	/**
	 * Retrieves menu voter
	 *
	 * @return VoterInterface
	 */
	private function createVoter()
	{
		// retrieving current request
		$request = Request::createFromGlobals();

		// cropping query string
		$uri = $request->getRequestUri();
		$uri = str_replace($request->getQueryString(), '', $uri);

		// trimming trailing "?"
		$uri = rtrim($uri, '?');

		return new UriVoter($uri);
	}
}
