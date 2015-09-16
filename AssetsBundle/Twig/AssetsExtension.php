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
			new \Twig_SimpleFunction('assets_script', [$this, 'assetsScript'], ['is_safe' => ['html']]),
			new \Twig_SimpleFunction('assets_style',  [$this, 'assetsStyle'], ['is_safe' => ['html']]),
		];
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	public function assetsScript($type = 'file')
	{
		return $this->assetsLoader->renderScript($type);
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	public function assetsStyle($type = 'file')
	{
		return $this->assetsLoader->renderStyle($type);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'docplanner_assets';
	}
}
