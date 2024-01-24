<?php

namespace Docplanner\AssetsBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Docplanner\AssetsBundle\Service\AssetsLoader;

class AssetsExtension extends AbstractExtension
{
	/** @var AssetsLoader $assetsLoader */
	private $assetsLoader;

	public function __construct(AssetsLoader $assetsLoader)
	{
		$this->assetsLoader = $assetsLoader;
	}

	public function getFunctions()
	{
		return [
			new TwigFunction('assets_*', [$this->assetsLoader, 'assets'], ['is_safe' => ['html']]),
		];
	}
}
