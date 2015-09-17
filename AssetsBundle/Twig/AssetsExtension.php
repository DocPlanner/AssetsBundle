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
	public function assetsScript($type = null)
	{
		return $this->assetsLoader->renderScript($type == 'inline' ? true : false);
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	public function assetsStyle($type = null)
	{
		return $this->assetsLoader->renderStyle($type == 'inline' ? true : false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'docplanner_assets';
	}
}
