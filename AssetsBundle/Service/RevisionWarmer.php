<?php
/**
 * Author: Łukasz Barulski
 * Date: 17.09.15 15:43
 */

namespace Docplanner\AssetsBundle\Service;

use Docplanner\AssetsBundle\IO\Asset;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

class RevisionWarmer extends CacheWarmer
{
	const CACHE_FILE_NAME = 'assetsRevisions.php';

	/**
	 * @var array
	 */
	private $config;
	/**
	 * @var AssetsRepository
	 */
	private $repository;

	/**
	 * @param AssetsRepository $repository
	 * @param array            $config
	 */
	public function __construct(AssetsRepository $repository, array $config)
	{
		$this->repository = $repository;
		$this->config     = $config;
	}


	/**
	 * Checks whether this warmer is optional or not.
	 * Optional warmers can be ignored on certain conditions.
	 * A warmer should return true if the cache can be
	 * generated incrementally and on-demand.
	 * @return Boolean true if the warmer is optional, false otherwise
	 */
	public function isOptional()
	{
		return false;
	}

	/**
	 * Warms up the cache.
	 *
	 * @param string $cacheDir The cache directory
	 */
	public function warmUp($cacheDir)
	{
		$data = $this->calculateHashes();
		$this->createPhpFile($cacheDir, $data);
	}

	/**
	 * @return array
	 */
	private function calculateHashes()
	{
		$data = [];
		foreach (['script' => $this->repository->getScripts(), 'style' => $this->repository->getStyles()] as $type => $assetsList)
		{
			/** @var Asset $asset */
			foreach ($assetsList as $assetName => $asset)
			{
				if (null !== $asset->getPath())
				{
					$data[$type][$assetName] = crc32(file_get_contents($asset->getPath()));
				}
			}
		}

		return $data;
	}

	/**
	 * @param string $cacheDir
	 * @param array  $data
	 */
	private function createPhpFile($cacheDir, array $data)
	{
		$data = '<?php' . PHP_EOL . 'return ' . var_export($data, true) . ';';
		$this->writeCacheFile($cacheDir . '/' . self::CACHE_FILE_NAME, $data);
	}
}