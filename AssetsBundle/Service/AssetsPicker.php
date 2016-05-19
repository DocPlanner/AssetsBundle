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
	 * @param RequestStack     $requestStack
	 * @param array            $config
	 */
	public function __construct(RequestStack $requestStack, array $config)
	{
		$this->requestStack = $requestStack;
		$this->config       = $config;
	}

	/**
	 * @param string $type
	 *
	 * @return string[] - asset names
	 */
	public function pickAssets($type)
	{
		if (false === array_key_exists($type, $this->config['types']))
		{
			throw new \LogicException(sprintf('Type "%s" is not defined!', $type));
		}

		$route = $this->requestStack->getMasterRequest() ? $this->requestStack->getMasterRequest()->get('_route') : null;

		$defaults = [];
		$picked = [];
		foreach ($this->config['types'][$type]['groups'] as $groupName => $group)
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
}