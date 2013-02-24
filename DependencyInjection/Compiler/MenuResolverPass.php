<?php

namespace NS\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MenuResolverPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
		if (!$container->hasDefinition('ns_admin.menu.resolver.collection')) {
			return;
		}

		$definition = $container->getDefinition('ns_admin.menu.resolver.collection');

		$resolvers = $container->findTaggedServiceIds('ns_admin.menu.resolver');
		foreach ($resolvers as $id => $attributes) {
			$definition->addMethodCall('add', array(new Reference($id)));
		}
    }
}
