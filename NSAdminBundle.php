<?php

namespace NS\AdminBundle;

use NS\AdminBundle\DependencyInjection\Compiler\MenuResolverPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NSAdminBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new MenuResolverPass());
	}
}
