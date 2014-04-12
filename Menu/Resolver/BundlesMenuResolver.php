<?php

namespace NS\AdminBundle\Menu\Resolver;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use NS\AdminBundle\Service\AdminService;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Yaml\Yaml;

/**
 * Bundles' menu resolver
 *
 */
class BundlesMenuResolver implements MenuResolverInterface
{
    const BUNDLE_MENU_NAVIGATION_FILE = '/Resources/config/ns_admin.navigation.yml';

    /**
     * @var AdminService
     */
    private $adminService;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var string
     */
    private $env;

    /**
     * @param AdminService     $adminService
     * @param FactoryInterface $factory
     * @param string           $env
     */
    public function __construct(AdminService $adminService, FactoryInterface $factory, $env)
    {
        $this->adminService = $adminService;
        $this->factory      = $factory;
        $this->env          = $env;
    }

    /**
     * @param ItemInterface $menu
     * @return void
     */
    public function resolve(ItemInterface $menu)
    {
        // adding bundles' menus
        foreach ($this->adminService->getActiveBundles() as $bundle) {
            $fileName = $this->getBundleNavigationFileName($bundle);
            if (file_exists($fileName)) {
                $yml = file_get_contents($fileName);
                foreach (Yaml::parse($yml) as $data) {
                    if (empty($data['env']) || $data['env'] == $this->env) {
                        $menu->addChild($this->createMenuItem($data, $bundle->getName()));
                    }
                }
            }
        }
    }

    /**
     * @param Bundle $bundle
     * @return string
     */
    private function getBundleNavigationFileName(Bundle $bundle)
    {
        return $bundle->getPath() . self::BUNDLE_MENU_NAVIGATION_FILE;
    }

    /**
     * @param array  $data
     * @param string $bundleName
     * @return ItemInterface
     */
    private function createMenuItem(array $data, $bundleName)
    {
        $item = $this->convertDataFormat($data, $bundleName);

        return $this->factory->createFromArray($item);
    }

    /**
     * Converts data format from ns_admin.navigation format to KNP-Menu
     *
     * @param  array  $data
     * @param  string $bundleName
     * @throws \Exception
     * @return array
     */
    private function convertDataFormat(array $data, $bundleName)
    {
        $route           = null;
        $routeParameters = null;
        $uri             = null;

        // admin route parameters
        if (!empty($data['action'])) {
            // if action value is empty ("mycontroller:" or "mycontroller")
            // using default value "index"
            $action = trim($data['action'], ':');

            // exploding route params (with default action value)
            $params          = explode(':', $action) + array(null, 'index');
            $adminController = $params[0];
            $adminAction     = $params[1];

            $route           = 'ns_admin_bundle';
            $routeParameters = array(
                'adminBundle'     => $bundleName,
                'adminController' => $adminController,
                'adminAction'     => $adminAction,
            );
        } else {
            $uri = '#';
        }

        // retrieving knp-menu formatted item config array
        $item = array(
            'name'            => !empty($data['name']) ? $data['name'] : uniqid('bundle_item_'),
            'label'           => !empty($data['label']) ? $data['label'] : uniqid(),
            'display'         => !isset($data['display']),
            'route'           => $route,
            'routeParameters' => $routeParameters,
            'uri'             => $uri,
            'extras'          => array(
                'position' => $this->getDataPosition($data),
                'icon'     => !empty($data['icon']) ? $data['icon'] : null,
                'parent'   => !empty($data['parent']) ? $data['parent'] : null,
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

    /**
     * @param array $data
     * @return int
     */
    private function getDataPosition($data)
    {
        // default value
        if (!isset($data['position'])) {
            return 50;
        }

        // named positions
        $dataPosition = $data['position'];
        $map          = array(
            'first'     => 0,
            'beginning' => 10,
            'normal'    => 50,
            'end'       => 90,
            'last'      => 100,
        );
        if (isset($map[$dataPosition])) {
            return $map[$dataPosition];
        }

        // numbered positions
        return (int)$dataPosition;
    }
}
