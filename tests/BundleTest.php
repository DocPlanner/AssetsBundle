<?php

namespace Docplanner\AssetsBundle\Tests;

use Docplanner\AssetsBundle\Service\AssetsLoader;
use Docplanner\AssetsBundle\Service\AssetsPicker;
use Docplanner\AssetsBundle\Service\AssetsProvider;
use Docplanner\AssetsBundle\Service\AssetsRepository;
use Docplanner\AssetsBundle\Tests\Fixtures\CustomAssetsLoader;
use Docplanner\AssetsBundle\Tests\Fixtures\TestKernel;
use Docplanner\AssetsBundle\Twig\AssetsExtension;
use LogicException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;

class BundleTest extends TestCase
{
	/** @var TestKernel[] */
	private $kernels = [];

	protected function tearDown(): void
	{
		foreach ($this->kernels as $kernel) {
			$kernel->shutdown();
			(new Filesystem())->remove($kernel->getTmpDir());
		}
		$this->kernels = [];
	}

	public function testBundleBootsAndRegistersServices(): void
	{
		$container = $this->bootKernel()->getContainer();

		self::assertInstanceOf(AssetsLoader::class, $container->get('docplanner_assets_bundle.service.assets_loader'));
		self::assertInstanceOf(AssetsProvider::class, $container->get('docplanner_assets.service.assets_provider'));
		self::assertInstanceOf(AssetsPicker::class, $container->get('docplanner_assets.service.assets_picker'));
		self::assertInstanceOf(AssetsRepository::class, $container->get('docplanner_assets.service.assets_repository'));
		self::assertInstanceOf(AssetsExtension::class, $container->get('docplanner_assets_bundle.twig.assets'));
		self::assertContains(Kernel::MAJOR_VERSION, [5, 6]);
	}

	public function testConfigIsResolvedIntoUrlsAndPaths(): void
	{
		$config = $this->bootKernel()->getContainer()->getParameter('docplanner_assets.config');
		$common = $config['types']['style']['assets']['common'];
		$cdn    = $config['types']['style']['assets']['cdn'];

		$path = $this->fixturesDir() . 'css/common.css';
		self::assertSame($path, $common['path']);
		self::assertSame('//static.example.com/css/common.css?v=' . crc32(file_get_contents($path)), $common['url']);
		self::assertNull($cdn['path']);
		self::assertSame('https://cdn.example.com/lib.css', $cdn['url']);
	}

	public function testRevisionsCanBeDisabled(): void
	{
		$config = $this->defaultConfig();
		$config['use_revisions'] = false;

		$loader = $this->bootKernel($config)->getContainer()->get('docplanner_assets_bundle.service.assets_loader');

		self::assertSame('//static.example.com/css/common.css', $loader->assets('style')['common']);
	}

	public function testLoaderReturnsDefaultGroupWithoutRequest(): void
	{
		$loader = $this->bootKernel()->getContainer()->get('docplanner_assets_bundle.service.assets_loader');

		self::assertSame(['common', 'cdn'], array_keys($loader->assets('style')));
		self::assertSame([], $loader->assets('style', true));
	}

	public function testLoaderPicksGroupForCurrentRoute(): void
	{
		$container = $this->bootKernel()->getContainer();
		$container->get('request_stack')->push(new Request([], [], ['_route' => 'homepage']));

		$loader = $container->get('docplanner_assets_bundle.service.assets_loader');

		self::assertSame(['common'], array_keys($loader->assets('style')));
		self::assertSame(['home' => 'h1{color:blue}'], $loader->assets('style', true));
	}

	public function testLoaderHonoursForcedRoute(): void
	{
		$loader = $this->bootKernel()->getContainer()->get('docplanner_assets_bundle.service.assets_loader');

		self::assertSame(['home' => 'h1{color:blue}'], $loader->assets('style', true, 'homepage'));
	}

	public function testManifestAssetsAreRegistered(): void
	{
		$loader = $this->bootKernel()->getContainer()->get('docplanner_assets_bundle.service.assets_loader');

		self::assertSame(
			['app' => '//static.example.com/js/app.js?v=' . crc32('console.log(1);'), 'app.js' => '//static.example.com/build/app.abc123.js'],
			$loader->assets('script')
		);
	}

	public function testUnknownTypeIsRejected(): void
	{
		$loader = $this->bootKernel()->getContainer()->get('docplanner_assets_bundle.service.assets_loader');

		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('Type "font" is not defined!');
		$loader->assets('font');
	}

	public function testMissingAssetFileFailsTheBuild(): void
	{
		$config = $this->defaultConfig();
		$config['types']['style']['assets']['missing'] = ['src' => 'css/missing.css'];

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('asset "missing"');
		$this->bootKernel($config);
	}

	public function testTwigFunctionsRenderAssets(): void
	{
		$container = $this->bootKernel()->getContainer();
		$container->get('request_stack')->push(new Request([], [], ['_route' => 'homepage']));

		$twig     = $container->get('twig');
		$template = $twig->createTemplate(
			'{% for s in assets_style() %}<link href="{{ s }}">{% endfor %}<style>{% for s in assets_style(true) %}{{ s }}{% endfor %}</style>'
		);

		self::assertSame(
			'<link href="//static.example.com/css/common.css?v=' . crc32('body{color:red}') . '"><style>h1{color:blue}</style>',
			$template->render([])
		);
	}

	public function testLoaderClassCanBeOverriddenByParameter(): void
	{
		$kernel = $this->bootKernel(null, ['docplanner_assets_bundle.service.assets_loader.class' => CustomAssetsLoader::class]);

		self::assertInstanceOf(CustomAssetsLoader::class, $kernel->getContainer()->get('docplanner_assets_bundle.service.assets_loader'));
	}

	private function bootKernel(?array $config = null, array $parameters = []): TestKernel
	{
		$kernel = new TestKernel($config ?? $this->defaultConfig(), $parameters);
		$this->kernels[] = $kernel;
		(new Filesystem())->remove($kernel->getTmpDir());
		$kernel->boot();

		return $kernel;
	}

	private function fixturesDir(): string
	{
		return __DIR__ . '/Fixtures/public/';
	}

	private function defaultConfig(): array
	{
		return [
			'base_host' => '//static.example.com/',
			'base_path' => $this->fixturesDir(),
			'types' => [
				'style' => [
					'assets' => [
						'common' => ['src' => 'css/common.css'],
						'home' => ['src' => 'css/home.css', 'inline' => true],
						'cdn' => ['src' => 'https://cdn.example.com/lib.css'],
					],
					'groups' => [
						'default' => ['assets' => ['common', 'cdn'], 'default' => true],
						'homepage' => ['assets' => ['common', 'home'], 'routes' => ['homepage']],
					],
				],
				'script' => [
					'manifest_file' => $this->fixturesDir() . 'build/manifest.json',
					'manifest_assets' => ['app.js'],
					'assets' => [
						'app' => ['src' => 'js/app.js'],
					],
					'groups' => [
						'default' => ['assets' => ['app', 'app.js'], 'default' => true],
					],
				],
			],
		];
	}
}
