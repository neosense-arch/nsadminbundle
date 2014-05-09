<?php

namespace NS\AdminBundle;

use NS\AdminBundle\DependencyInjection\Compiler\MenuResolverPass;
use NS\CoreBundle\Bundle\CoreBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NSAdminBundle extends Bundle implements CoreBundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new MenuResolverPass());
	}

    /**
     * Retrieves human-readable bundle title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Администрирование';
    }
}
