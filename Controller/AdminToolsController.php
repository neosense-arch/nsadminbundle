<?php

namespace NS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Knp\Menu\ItemInterface;

class AdminToolsController extends Controller
{
	/**
	 * @throws \Exception
	 * @return Response
	 */
	public function phpinfoAction()
	{
        ob_start();
        phpinfo();
        $phpInfo = ob_get_contents();
        ob_end_clean();

        $phpInfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$phpInfo);

		return $this->render('NSAdminBundle:AdminTools:phpinfo.html.twig', array(
            'phpInfo' => $phpInfo,
        ));
	}
}
