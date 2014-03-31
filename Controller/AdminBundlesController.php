<?php

namespace NS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AdminBundlesController
 *
 * @package NS\AdminBundle\Controller
 */
class AdminBundlesController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
	public function indexAction(Request $request)
	{
        $fileName = $this->container->getParameter('kernel.root_dir') . '/config/ns_admin.bundles.yml';

        if (file_exists($fileName)) {
            $yml = Yaml::parse(file_get_contents($fileName));
        }
        else {
            $yml = array(
                'ns_admin' => array(
                    'bundles' => array('NSAdminBundle', 'NSCmsBundle', 'NSUserBundle'),
                ),
            );
        }

        /** @var Kernel $kernel */
        $kernel = $this->get('kernel');
        $bundles = $kernel->getBundles();

        $choices = array();
        foreach ($bundles as $bundle) {
            $choices[$bundle->getName()] = $bundle->getName();
        }

        $form = $this->createFormBuilder()
            ->add('bundles', 'choice', array(
                'required' => true,
                'label'    => 'Бандлы',
                'choices'  => $choices,
                'multiple' => true,
                'expanded' => true,
            ))
            ->getForm()
            ->setData($yml['ns_admin']);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $yml['ns_admin']['bundles'] = array_values($data['bundles']);
            file_put_contents($fileName, Yaml::dump($yml));
            return $this->redirect($this->generateUrl('ns_admin_bundle', array(
                'adminBundle'     => 'NSAdminBundle',
                'adminController' => 'bundles',
                'adminAction'     => 'index',
            )));
        }

        return $this->render('NSAdminBundle:AdminBundles:index.html.twig', array(
            'form' => $form->createView(),
        ));
	}
}
