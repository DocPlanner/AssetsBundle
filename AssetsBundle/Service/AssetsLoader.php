<?php

namespace Docplanner\AssetsBundle\Service;

class AssetsLoader
{
	/** @var AssetsProvider */
	private $assetsProvider;

	/**
	 * @param AssetsProvider $assetsProvider
	 */
	public function __construct(AssetsProvider $assetsProvider)
	{
		$this->assetsProvider = $assetsProvider;
	}

	/**
	 * @param string $type
	 * @param bool   $inline
	 *
	 * @return string[]
	 */
	public function assets($type, $inline = false)
	{
		$result = [];
		foreach ($this->assetsProvider->getAssets($type) as $assetName => $asset)
		{
			if ($inline !== $asset->isInline())
			{
				continue;
			}

			$result[$assetName] = $inline ? file_get_contents($asset->getPath()) : $asset->getUrl();
		}

		return $result;
	}
}
