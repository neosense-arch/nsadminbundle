<?php

namespace NS\AdminBundle\Controller;

use NS\CoreBundle\Service\ChangelogService;
use NS\CoreBundle\Service\VersionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        // phpinfo
        ob_start();
        phpinfo();
        $phpInfo = ob_get_contents();
        ob_end_clean();
        $phpInfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$phpInfo);

		return $this->render('NSAdminBundle:AdminTools:info.html.twig', array(
            'phpInfo'    => $phpInfo,
            'cmsVersion' => $this->get('ns_core.service.version')->getVersion(),
            'changelog'  => $this->get('ns_core.service.changelog')->getChangelog(),
            'info'       => $this->get('ns_core.service.phpinfo')->getInfo(),
        ));
	}
}
