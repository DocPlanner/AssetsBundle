<?php

namespace Docplanner\AssetsBundle\Twig;

class AsseticExtension extends Twig_Extension
{
	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('assets_script', [$this, 'assetsScript']),
			new Twig_SimpleFunction('assets_style',  [$this, 'assetsStyle']),
		];
	}

	protected function assetsScript($type = '')
	{
		return 'script';
	}

	protected function assetsStyle($type = '')
	{
		return 'style';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'docplanner_assets_extension';
	}
}