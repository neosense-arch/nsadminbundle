<?php

namespace NS\AdminBundle\Controller;

use NS\CoreBundle\Service\VersionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Knp\Menu\ItemInterface;

/**
 * Class AdminToolsController
 *
 * @package NS\AdminBundle\Controller
 */
class AdminToolsController extends Controller
{
	/**
	 * @throws \Exception
	 * @return Response
	 */
	public function infoAction()
	{
        // cms info
        /** @var VersionService $versionService */
        $versionService = $this->get('ns_core.service.version');

        // phpinfo
        ob_start();
        phpinfo();
        $phpInfo = ob_get_contents();
        ob_end_clean();
        $phpInfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$phpInfo);

		return $this->render('NSAdminBundle:AdminTools:info.html.twig', array(
            'phpInfo'    => $phpInfo,
            'cmsVersion' => $versionService->getVersion(),
        ));
	}
}
