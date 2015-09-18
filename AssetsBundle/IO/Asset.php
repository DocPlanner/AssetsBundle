<?php
/**
 * Author: Łukasz Barulski
 * Date: 16.09.15 14:11
 */

namespace Docplanner\AssetsBundle\IO;

class Asset
{
	/** @var string|null */
	private $path;

	/** @var string */
	private $url;

	/** @var bool */
	private $inline;

	/**
	 * @param string      $url
	 * @param string|null $path
	 * @param bool        $inline
	 */
	public function __construct($url, $path = null, $inline = false)
	{
		$this->url    = $url;
		$this->path   = $path;
		$this->inline = $inline;
	}

	/**
	 * @return string|null
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return boolean
	 */
	public function isInline()
	{
		return $this->inline;
	}
}