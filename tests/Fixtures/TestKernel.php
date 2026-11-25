<?php

namespace Docplanner\AssetsBundle\Tests\Fixtures;

use Docplanner\AssetsBundle\DocplannerAssetsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
	/** @var array */
	private $assetsConfig;

	/** @var array */
	private $parameters;

	public function __construct(array $assetsConfig, array $parameters = [])
	{
		$this->assetsConfig = $assetsConfig;
		$this->parameters   = $parameters;

		parent::__construct('test', true);
	}

	public function registerBundles(): iterable
	{
		return [
			new FrameworkBundle(),
			new TwigBundle(),
			new DocplannerAssetsBundle(),
		];
	}

	public function registerContainerConfiguration(LoaderInterface $loader): void
	{
		$loader->load(function (ContainerBuilder $container): void {
			$container->loadFromExtension('framework', [
				'secret' => 'test',
				'http_method_override' => false,
			]);
			$container->loadFromExtension('twig', [
				'strict_variables' => true,
			]);
			$container->loadFromExtension('docplanner_assets', $this->assetsConfig);

			foreach ($this->parameters as $name => $value) {
				$container->setParameter($name, $value);
			}
		});
	}

	protected function build(ContainerBuilder $container): void
	{
		// expose the bundle services so tests can fetch them directly
		$container->addCompilerPass(new PublicServicesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
	}

	public function getProjectDir(): string
	{
		return __DIR__;
	}

	public function getCacheDir(): string
	{
		return $this->getTmpDir() . '/cache';
	}

	public function getLogDir(): string
	{
		return $this->getTmpDir() . '/logs';
	}

	public function getTmpDir(): string
	{
		return sys_get_temp_dir() . '/assets-bundle-tests/' . md5(serialize([$this->assetsConfig, $this->parameters]));
	}
}
