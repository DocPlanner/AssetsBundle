<?php
/**
 * Author: Łukasz Barulski
 * Date: 16.09.15 14:11
 */

namespace Docplanner\AssetsBundle\IO;

class Asset
{
	/** @var string */
	private $src;

	/** @var string */
	private $inline;

	/**
	 * @param string $src
	 * @param string $inline
	 */
	public function __construct($src, $inline)
	{
		$this->src    = $src;
		$this->inline = $inline;
	}

	/**
	 * @return string
	 */
	public function getSrc()
	{
		return $this->src;
	}

	/**
	 * @return string
	 */
	public function getInline()
	{
		return $this->inline;
	}
}