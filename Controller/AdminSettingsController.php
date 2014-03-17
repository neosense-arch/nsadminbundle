<?php

namespace NS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Knp\Menu\ItemInterface;

/**
 * Class AdminSettingsController
 *
 * @package NS\AdminBundle\Controller
 */
class AdminSettingsController extends Controller
{
	/**
     * Redirects to first settings subpage
     *
	 * @throws \Exception
	 * @return Response
	 */
	public function indexAction()
	{
		/** @var ItemInterface $menu  */
		$menu = $this->get('ns_admin.menu.main');

		if (!$menu->offsetExists('settings')) {
			throw new \Exception("Menu item 'settings' wasn't found");
		}

		$settings = $menu->offsetGet('settings');
		if (!$settings->hasChildren()) {
			throw new \Exception("It seems like settings is empty");
		}

		/** @var $firstChild ItemInterface */
		$firstChild = $settings->getFirstChild();
		return $this->redirect($firstChild->getUri());
	}
}
