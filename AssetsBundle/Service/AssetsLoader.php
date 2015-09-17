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
		$mask   = $type == 'inline' ? '<script>%s</script>' : '<script src="%s?%s"></script>';

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
		$mask   = $type == 'inline' ? '<style>%s</style>' : '<link rel="stylesheet" type="text/css" href="%s?%s">';

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
				if ($asset->getSrc())
				{
					$ret .= sprintf($mask, $asset->getSrc(), crc32(file_get_contents($asset->getSrc())));
				}
			}
		}
		else
		{
			foreach ($assets as $asset)
			{
				if ($asset->getInline())
				{
					$ret .= file_get_contents($asset->getInline());
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
