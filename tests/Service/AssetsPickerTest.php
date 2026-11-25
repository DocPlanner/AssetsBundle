<?php

namespace Docplanner\AssetsBundle\Tests\Service;

use Docplanner\AssetsBundle\Service\AssetsPicker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AssetsPickerTest extends TestCase
{
	private const CONFIG = [
		'types' => [
			'style' => [
				'groups' => [
					'base' => ['assets' => ['a', 'b'], 'default' => true, 'routes' => []],
					'extra' => ['assets' => ['b', 'c'], 'default' => true, 'routes' => []],
					'one' => ['assets' => ['a', 'd'], 'default' => false, 'routes' => ['r1', 'r2']],
					'two' => ['assets' => ['d', 'e'], 'default' => false, 'routes' => ['r2']],
				],
			],
		],
	];

	public function testDefaultsAreMergedAndUnique(): void
	{
		$picker = new AssetsPicker(new RequestStack(), self::CONFIG);

		self::assertSame(['a', 'b', 'c'], array_values($picker->pickAssets('style')));
	}

	public function testRouteGroupsWinOverDefaults(): void
	{
		$stack = new RequestStack();
		$stack->push(new Request([], [], ['_route' => 'r2']));
		$picker = new AssetsPicker($stack, self::CONFIG);

		self::assertSame(['a', 'd', 'e'], array_values($picker->pickAssets('style')));
	}

	public function testForcedRouteIgnoresCurrentRequest(): void
	{
		$stack = new RequestStack();
		$stack->push(new Request([], [], ['_route' => 'r2']));
		$picker = new AssetsPicker($stack, self::CONFIG);

		self::assertSame(['a', 'd'], array_values($picker->pickAssets('style', 'r1')));
	}

	public function testUnknownRouteFallsBackToDefaults(): void
	{
		$picker = new AssetsPicker(new RequestStack(), self::CONFIG);

		self::assertSame(['a', 'b', 'c'], array_values($picker->pickAssets('style', 'nope')));
	}
}
