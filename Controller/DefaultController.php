<?php

namespace NS\AdminBundle\Controller;

use NS\AdminBundle\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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
        return $this->redirect($this->generateUrl('ns_admin_bundle', array(
            'adminBundle'     => 'NSCmsBundle',
            'adminController' => 'content',
            'adminAction'     => 'index',
        )));
	}

    /**
     * Renders bundle's admin page
     *
     * @param Request $request
     * @param  string $adminBundle
     * @param  string $adminController
     * @param  string $adminAction
     * @return Response
     */
	public function bundleAction(Request $request, $adminBundle, $adminController, $adminAction)
	{
		/** @var $service AdminService */
		$service = $this->get('ns_admin.service');

        // retrieving controller name
		$controller = $service->getAdminRouteController($adminBundle, $adminController, $adminAction);

        // creating subrequest
        $path = $request->attributes->all();
        $path['_controller'] = $controller;
        $subRequest = $request->duplicate(null, null, $path);

        return $this->container->get('http_kernel')->handle($subRequest);
	}
}
