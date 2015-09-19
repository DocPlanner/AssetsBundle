<?php
/**
 * Author: Łukasz Barulski
 * Date: 17.09.15 16:43
 */

namespace Docplanner\AssetsBundle\Service;

use Docplanner\AssetsBundle\IO\Asset;

class AssetsRepository
{
	/** @var Asset[][]|null */
	private $data = [];

	/** @var array */
	private $config;

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @param string $type
	 *
	 * @return Asset[]
	 */
	public function getAssetsList($type)
	{
		if (array_key_exists($type, $this->data))
		{
			return $this->data[$type];
		}

		if (false === array_key_exists($type, $this->config['types']))
		{
			throw new \LogicException(sprintf('Type "%s" is not defined!', $type));
		}

		$this->data[$type] = [];
		foreach ($this->config['types'][$type]['assets'] as $assetName => $asset)
		{
			$this->data[$type][$assetName] = new Asset($asset['url'], $asset['path'], $asset['inline']);
		}

		return $this->data[$type];
	}

	/**
	 * @param string $type
	 * @param string $assetName
	 *
	 * @return Asset
	 */
	public function getAsset($type, $assetName)
	{
		$assets = $this->getAssetsList($type);

		if (false === array_key_exists($assetName, $assets))
		{
			throw new \LogicException(sprintf('Asset "%s" not defined in type "%s"!', $assetName, $type));
		}

		return $assets[$assetName];
	}
}