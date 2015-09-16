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
			new \Twig_SimpleFunction('assets_script', [$this, 'assetsScript']),
			new \Twig_SimpleFunction('assets_style',  [$this, 'assetsStyle']),
		];
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	protected function assetsScript($type = null)
	{
		return '';
//		return $this->assetsLoader->renderScript($type);
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	protected function assetsStyle($type = null)
	{
		return $this->assetsLoader->renderScript($type);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'docplanner_assets';
	}
}
