<?php
/**
 * Author: Łukasz Barulski
 * Date: 17.09.15 16:43
 */

namespace Docplanner\AssetsBundle\Service;

use Docplanner\AssetsBundle\IO\Asset;

class AssetsRepository
{
	/** @var Asset[]|null */
	private $script;

	/** @var Asset[]|null */
	private $style;

	/** @var array */
	private $config;

	/** @var array|null */
	private $cache;

	/**
	 * @param array  $config
	 * @param string $cacheDir
	 */
	public function __construct(array $config, $cacheDir)
	{
		$this->config = $config;

		$cacheFileName = $cacheDir . '/' . RevisionWarmer::CACHE_FILE_NAME;
		if (file_exists($cacheFileName))
		{
			$this->cache = require $cacheFileName;
		}
	}

	/**
	 * @return \Docplanner\AssetsBundle\IO\Asset[]
	 */
	public function getScripts()
	{
		if (null === $this->script)
		{
			$this->script = $this->getAssets('script');
		}

		return $this->script;
	}

	/**
	 * @return \Docplanner\AssetsBundle\IO\Asset[]
	 */
	public function getStyles()
	{
		if (null === $this->style)
		{
			$this->style = $this->getAssets('style');
		}

		return $this->style;
	}

	/**
	 * @param string $type
	 *
	 * @return Asset[]
	 */
	private function getAssets($type)
	{
		$data = [];
		foreach ($this->config[$type]['assets'] as $assetName => $asset)
		{
			if ($asset['remote'])
			{
				$url = $asset['src'];
				$path = null;
			}
			else
			{

				$url = $this->config['base']['host'] . $asset['src'];
				$path = $this->config['base']['path'] . $asset['src'];
				if ([] !== $this->cache && array_key_exists($type, $this->cache) && array_key_exists($assetName, $this->cache[$type]))
				{
					$url .= '?' . $this->cache[$type][$assetName];
				}
			}

			$data[$assetName] = new Asset($url, $path, $asset['inline']);
		}

		return $data;
	}
}