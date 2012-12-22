<?php

namespace NS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default admin controller
 *
 */
class DefaultController extends Controller
{
	/**
	 * Index page
	 *
	 * @return Response
	 */
	public function indexAction()
	{
		return $this->render('NSAdminBundle:Default:index.html.twig');
	}

	/**
	 * Renders bundle's admin page
	 *
	 * @param  string $adminBundle
	 * @param  string $adminController
	 * @param  string $adminAction
	 * @return Response
	 */
	public function bundleAction($adminBundle, $adminController, $adminAction)
	{
		$adminController = 'Admin' . ucfirst($adminController);
		return $this->forward("{$adminBundle}:{$adminController}:{$adminAction}");
	}
}
