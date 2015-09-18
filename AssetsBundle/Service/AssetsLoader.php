<?php

namespace Docplanner\AssetsBundle\Service;

use Docplanner\AssetsBundle\IO\Asset;

class AssetsLoader
{

	/** @var AssetsPicker $assetsPicker */
	private $asstsPicker;

	/**
	 * @param AssetsPicker $assetsPicker
	 */
	public function __construct(AssetsPicker $assetsPicker)
	{
		$this->asstsPicker = $assetsPicker;
	}

	/**
	 * @param bool|false $isInline
	 *
	 * @return string
	 */
	public function renderScript($isInline = false)
	{
		$assets = $this->asstsPicker->pickScriptAssets();
		$mask   = $isInline ? '<script>%s</script>' : '<script src="%s"></script>';

		return $this->render($mask, $assets, $isInline);
	}

	/**
	 * @param bool|false $isInline
	 *
	 * @return string
	 */
	public function renderStyle($isInline = false)
	{
		$assets = $this->asstsPicker->pickStyleAssets();
		$mask   = $isInline ? '<style>%s</style>' : '<link rel="stylesheet" type="text/css" href="%s">';

		return $this->render($mask, $assets, $isInline);
	}

	/**
	 * @param String  $mask
	 * @param Asset[] $assets
	 * @param boolean $isInline
	 *
	 * @return string
	 */
	private function render($mask, $assets, $isInline)
	{
		$ret = '';

		if (!$isInline)
		{
			foreach ($assets as $asset)
			{
				$ret .= sprintf($mask, $asset->getUrl());
			}
		}
		else
		{
			foreach ($assets as $asset)
			{
				if ($asset->isInline())
				{
					$ret .= file_get_contents($asset->getPath() ?: $asset->getUrl());
				}
			}

			if ($ret)
			{
				$ret = sprintf($mask, $ret);
			}
		}

		return $ret;
	}
}
