<?php

namespace Docplanner\AssetsBundle\Tests\Fixtures;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PublicServicesPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container): void
	{
		foreach ($container->getDefinitions() as $id => $definition) {
			if (0 === strpos($id, 'docplanner_assets') || 'twig' === $id || 'request_stack' === $id) {
				$definition->setPublic(true);
			}
		}
	}
}
