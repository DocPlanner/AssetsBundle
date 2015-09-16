<?php
/**
 * Author: Łukasz Barulski
 * Date: 16.09.15 13:59
 */

namespace Docplanner\AssetsBundle\Service;

use Docplanner\AssetsBundle\IO\Asset;
use Symfony\Component\HttpFoundation\RequestStack;

class AssetsPicker
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * @var array
	 */
	private $config;

	/**
	 * @param RequestStack $requestStack
	 * @param array        $config
	 */
	public function __construct(RequestStack $requestStack, array $config)
	{
		$this->requestStack = $requestStack;
		$this->config       = $config;
	}

	/**
	 * @return Asset[]
	 */
	public function pickStyleAssets()
	{
		return $this->getAssets('style');
	}

	/**
	 * @return Asset[]
	 */
	public function pickScriptAssets()
	{
		return $this->getAssets('script');
	}

	/**
	 * @param string $type
	 *
	 * @return string[] - asset names
	 */
	private function pickAssets($type)
	{
		$route = $this->requestStack->getMasterRequest()->get('_route');

		$defaults = [];
		$picked = [];
		foreach ($this->config[$type]['groups'] as $groupName => $group)
		{
			if (is_array($group['routes']) && in_array($route, $group['routes']))
			{
				$picked = array_merge($picked, $group['assets']);
			}

			if ($group['default'])
			{
				$defaults = array_merge($defaults, $group['assets']);
			}
		}

		if (0 === count($picked))
		{
			array_unique($defaults);

			return $defaults;
		}

		array_unique($picked);

		return $picked;
	}

	/**
	 * @param string $type
	 *
	 * @return Asset[]
	 */
	private function getAssets($type)
	{
		$assets = [];
		foreach ($this->pickAssets($type) as $asset)
		{
			if (false === array_key_exists($asset, $this->config[$type]['assets']))
			{
				throw new \RuntimeException(sprintf('Asset "%s" not found', $asset));
			}

			$assetConfig = $this->config[$type]['assets'][$asset];

			$assets[] = new Asset($assetConfig['src'], $assetConfig['inline']);
		}

		return $assets;
	}
}