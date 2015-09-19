<?php

namespace Docplanner\AssetsBundle\Twig;

use Docplanner\AssetsBundle\Service\AssetsLoader;

class AssetsExtension extends \Twig_Extension
{
	/** @var AssetsLoader $assetsLoader */
	private $assetsLoader;

	/**
	 * @param AssetsLoader $assetsLoader
	 */
	public function __construct(AssetsLoader $assetsLoader)
	{
		$this->assetsLoader = $assetsLoader;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('assets_*', [$this->assetsLoader, 'assets'], ['is_safe' => ['html']]),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'docplanner_assets';
	}
}
