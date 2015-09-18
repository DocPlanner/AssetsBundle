<?php

namespace Docplanner\AssetsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DocplannerAssetsExtension extends Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config        = $this->processConfiguration($configuration, $configs);

		foreach (['style', 'script'] as $type)
		{
			foreach ($config[$type]['assets'] as $assetName => &$asset)
			{
				$src = trim($asset['src']);
				$isNetworkResource = false;
				if (0 === strpos($src, '//') || false !== filter_var($src, FILTER_VALIDATE_URL))
				{
					$isNetworkResource = true;
				}

				$asset['remote'] = $isNetworkResource;
			}
		}

		$container->setParameter('docplanner_assets.config', $config);

		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
