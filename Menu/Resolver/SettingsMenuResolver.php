<?php

namespace NS\AdminBundle\Menu\Resolver;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use NS\AdminBundle\Service\AdminService;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Yaml\Yaml;

class SettingsMenuResolver implements MenuResolverInterface
{
	const SETTINGS_MENU_NAVIGATION_FILE = '/Resources/config/ns_admin.settings.yml';

	/**
	 * @var AdminService
	 */
	private $adminService;

	/**
	 * @var FactoryInterface
	 */
	private $factory;

	/**
	 * @param AdminService     $adminService
	 * @param FactoryInterface $factory
	 */
	public function __construct(AdminService $adminService, FactoryInterface $factory)
	{
		$this->adminService = $adminService;
		$this->factory = $factory;
	}

	/**
	 * @param ItemInterface $menu
	 * @return void
	 */
	public function resolve(ItemInterface $menu)
	{
		$settings = $this->getSettingsMenuItem($menu);

		foreach ($this->adminService->getActiveBundles() as $bundle) {
			$fileName = $this->getSettingsFileName($bundle);
			if (file_exists($fileName)) {
				$yml = file_get_contents($fileName);
				foreach (Yaml::parse($yml) as $data) {
					$item = $this->convertDataFormat($data, $bundle->getName(), 'settings-' . $bundle->getName());
					$settings->addChild($this->factory->createFromArray($item));
				}
			}
		}
	}

	/**
	 * @param  ItemInterface $menu
	 * @return ItemInterface
	 */
	private function getSettingsMenuItem(ItemInterface $menu)
	{
		// searching for settings item
		/** @var ItemInterface $subItem */
		foreach ($menu->getChildren() as $subItem) {
			if ($subItem->getName() === 'settings') {
				return $subItem;
			}
		}

		// creating item
		$item = $this->factory->createFromArray(array(
			'name'  => 'settings',
			'label' => 'Настройки',
			'route' => 'ns_admin_bundle',
			'routeParameters' => array(
				'adminBundle'     => 'NSAdminBundle',
				'adminController' => 'Settings',
				'adminAction'     => 'index',
			),
			'extras' => array(
				'controller' => 'NSAdminBundle:AdminSettings:index'
			),
			'displayChildren' => false
		));
		$menu->addChild($item);

		return $item;
	}

	/**
	 * @param Bundle $bundle
	 * @return string
	 */
	private function getSettingsFileName(Bundle $bundle)
	{
		return $bundle->getPath() . self::SETTINGS_MENU_NAVIGATION_FILE;
	}

	/**
	 * Converts data format from ns_admin.navigation format to KNP-Menu
	 *
	 * @param  array  $data
	 * @param  string $bundleName
	 * @param  string $name
	 * @throws \Exception
	 * @return array
	 */
	private function convertDataFormat(array $data, $bundleName, $name = null)
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
			'name'  => $name ?: uniqid(),
			'label' => $data['label'],
			'route' => 'ns_admin_bundle',
			'routeParameters' => array(
				'adminBundle'     => $bundleName,
				'adminController' => 'Settings' . ucfirst($params[0]),
				'adminAction'     => $params[1],
			),
		);

		// recursively adding child items
		if (!empty($data['pages'])) {
			foreach ($data['pages'] as $page) {
				$item['children'][] = $this->convertDataFormat($page, $bundleName);
			}
		}

		return $item;
	}
}
