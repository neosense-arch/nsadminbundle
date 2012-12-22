<?php

namespace NS\AdminBundle\Menu;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

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
class Builder extends ContainerAware
{
	/**
	 * Factory
	 * @var FactoryInterface
	 */
	private $factory;

	/**
	 * Bundles
	 * @var string[]
	 */
	private $bundles;

	/**
	 * Constructor
	 *
	 * @param  FactoryInterface $factory
	 * @param  string[] $bundles
	 * @return Builder
	 */
	public function __construct(FactoryInterface $factory, array $bundles)
	{
		$this->factory = $factory;
		$this->bundles = $bundles;
	}

	/**
	 * Creates main menu
	 *
	 * @return ItemInterface
	 */
	public function createMainMenu()
	{
		// adding route voter to menu matcher
		$this->getMatcher()->addVoter($this->createVoter());

		// root node
		$menu = $this->factory
			->createItem('root')
			->setChildrenAttribute('class', 'nav')
		;

		// adding bundles' menus
		foreach ($this->bundles as $bundleName) {
			$bundle = $this->getKernel()->getBundle($bundleName);
			$fileName = $bundle->getPath() . '/Resources/config/ns_admin.navigation.yml';
			if (file_exists($fileName)) {
				$yml = file_get_contents($fileName);
				foreach (Yaml::parse($yml) as $data) {
					$item = $this->convertDataFormat($data);
					$item['routeParameters']['adminBundle'] = $bundleName;

					$menu->addChild($this->factory->createFromArray($item));
				}
			}
		}

		return $menu;
	}

	/**
	 * Converts data format from ns_admin.navigation format to KNP-Menu
	 *
	 * @param  array $data
	 * @throws \Exception
	 * @return array
	 */
	private function convertDataFormat(array $data)
	{
		// required params
		if (empty($data['label'])) {
			throw new \Exception('Required attribute "label" is missing');
		}
		if (empty($data['action'])) {
			throw new \Exception('Required attribute "action" is missing');
		}

		// if action value is empty ("mycontroller:" or "mycontroller")
		// using default value "index"
		$action = trim($data['action'], ':');

		// exploding route params (with default action value)
		$params = explode(':', $action) + array(null, 'index');

		// retrieving knp-menu formatted item config array
		$item = array(
			'name'  => uniqid(),
			'label' => $data['label'],
			'route' => 'ns_admin_bundle',
			'routeParameters' => array(
				'adminBundle'     => null,
				'adminController' => $params[0],
				'adminAction'     => $params[1],
			),
		);

		// recursively adding child items
		if (!empty($data['pages'])) {
			foreach ($data['pages'] as $page) {
				$item['children'][] = $this->convertDataFormat($page);
			}
		}

		return $item;
	}

	/**
	 * Retrieves menu matcher
	 *
	 * @return Matcher
	 */
	private function getMatcher()
	{
		return $this->container->get('knp_menu.matcher');
	}

	/**
	 * Retrieves kernel
	 *
	 * @return Kernel
	 */
	private function getKernel()
	{
		return $this->container->get('kernel');
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
