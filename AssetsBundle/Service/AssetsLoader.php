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
	 * @param null $type
	 *
	 * @return string
	 */
	public function renderScript($type = 'src')
	{
		$assets = $this->asstsPicker->pickScriptAssets();
		$mask  = $type == 'inline' ? '<script>%s</script>' : '<script src="%s?%s"></script>';

		return $this->render($mask, $assets, $type);
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	public function renderStyle($type = 'src')
	{
		$assets = $this->asstsPicker->pickStyleAssets();
		$mask  = $type == 'inline' ? '<style>%s</style>' : '<link rel="stylesheet" type="text/css" href="%s?%s">';

		return $this->render($mask, $assets, $type);
	}

	/**
	 * @param         $mask
	 * @param Asset[] $assets
	 * @param         $type
	 *
	 * @return string
	 */
	private function render($mask, $assets, $type)
	{
		$ret = '';

		if ($type == 'src')
		{
			foreach ($assets as $asset)
			{
				if (!file_exists($asset->getSrc()))
				{
					throw new \LogicException(sprintf('File %s not found', $asset->getSrc()));
				}

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
				if (!file_exists($asset->getInline()))
				{
					throw new \LogicException(sprintf('File %s not found', $asset->getInline()));
				}

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



