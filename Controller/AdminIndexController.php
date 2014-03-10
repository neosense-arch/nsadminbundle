<?php

namespace NS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Knp\Menu\ItemInterface;

class AdminIndexController extends Controller
{
	/**
	 * @throws \Exception
	 * @return Response
	 */
	public function indexAction()
	{
		return $this->redirect($this->generateUrl('ns_admin_index'));
	}
}
