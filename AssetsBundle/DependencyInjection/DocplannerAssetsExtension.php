<?php

namespace Docplanner\AssetsBundle\DependencyInjection;

use RuntimeException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
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
	protected static $parsedManifests = [];

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config        = $this->processConfiguration($configuration, $configs);

		foreach ($config['types'] as &$typeConfig)
		{
			foreach ($typeConfig['assets'] as $assetName => &$asset)
			{
				$src = trim($asset['src']);
				if (0 === strpos($src, '//') || false !== filter_var($src, FILTER_VALIDATE_URL))
				{
					$url = $asset['src'];
					$path = null;
				}
				else
				{
					$url = $config['base_host'] . $asset['src'];
					$path = $config['base_path'] . $asset['src'];

					if (false === file_exists($path))
					{
						throw new RuntimeException(sprintf('File "%s" not found(asset "%s")!', $path, $assetName));
					}

					if ($config['use_revisions'])
					{
						$url .= '?v=' . crc32(file_get_contents($path));
					}
				}

				$asset['url'] = $url;
				$asset['path'] = $path;
			}

			$manifest = [];

			if(!empty($typeConfig['manifest_file']))
			{
				$manifest = $this->loadManifestAssets($typeConfig['manifest_file']);
			}

			foreach($typeConfig['manifest_assets'] ?? [] as $manifestKey)
			{
				if(!empty($manifest[$manifestKey]))
				{
					$manifestAsset = $manifest[$manifestKey];

					$typeConfig['assets'][$manifestKey] = [
						'src' => $manifestAsset,
						'url' => $config['base_host'].$manifestAsset,
						'path' => $config['base_path'].$manifestAsset,
						'inline' => false,
					];
				}
			}
		}

		$container->setParameter('docplanner_assets.config', $config);

		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    protected function loadManifestAssets($manifestFile)
	{
		if(!array_key_exists($manifestFile, self::$parsedManifests))
		{
			if(is_file($manifestFile) && is_readable($manifestFile))
			{
				$manifest = file_get_contents($manifestFile);
				$manifest = @json_decode($manifest, true);

				if(!is_array($manifest))
				{
					throw new RuntimeException(sprintf('Cannot parse manifest file `%s`', $manifestFile));
				}

				self::$parsedManifests[$manifestFile] = $manifest;
			}
			else
			{
				throw new RuntimeException(sprintf('Manifest file `%s` not found', $manifestFile));
			}
		}

		return self::$parsedManifests[$manifestFile];
	}
}
