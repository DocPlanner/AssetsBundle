<?php
/**
 * Author: Łukasz Barulski
 * Date: 19.09.15 11:01
 */

namespace Docplanner\AssetsBundle\Service;

use Docplanner\AssetsBundle\IO\Asset;

class AssetsProvider
{
	/** @var AssetsRepository */
	private $assetsRepository;

	/** @var AssetsPicker */
	private $assetsPicker;

	/**
	 * @param AssetsRepository $assetsRepository
	 * @param AssetsPicker     $assetsPicker
	 */
	public function __construct(AssetsRepository $assetsRepository, AssetsPicker $assetsPicker)
	{
		$this->assetsRepository = $assetsRepository;
		$this->assetsPicker     = $assetsPicker;
	}

	/**
	 * @param string $type
	 * @param $forceRoute
	 *
	 * @return Asset[]
	 */
	public function getAssets($type, $forceRoute = null)
	{
		$chosenAssets = $this->assetsPicker->pickAssets($type, $forceRoute);
		if ([] === $chosenAssets)
		{
			return [];
		}

		$assets = [];
		foreach ($chosenAssets as $asset)
		{
			$assets[$asset] = $this->assetsRepository->getAsset($type, $asset);
		}

		return $assets;
	}
}