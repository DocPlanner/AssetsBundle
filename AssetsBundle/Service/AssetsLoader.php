<?php

namespace Docplanner\AssetsBundle\Service;

class AssetsLoader
{
	/**
	 * @param null $type
	 *
	 * @return string
	 */
	public function renderScript($type = 'file')
	{
		$files = ['platform/js/rwd-common.js'];

		$mask = $type == 'inline' ? '<script>%s</script>' : '<script src="%s?%s"></script>';

		return $this->render($mask, $files, $type);
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	public function renderStyle($type = 'file')
	{
		$files = ['platform/css/rwd-common.css'];

		$mask = $type == 'inline' ? '<style>%s</style>' : '<link rel="stylesheet" type="text/css" href="%s?%s">';

		return $this->render($mask, $files, $type);
	}

	/**
	 * @param $mask
	 * @param $files
	 * @param $type
	 *
	 * @return string
	 */
	private function render($mask, $files, $type)
	{
		$ret = '';

		if ($type == 'file')
		{
			foreach ($files as $file)
			{
				if (!file_exists($file))
				{
					throw new \LogicException(sprintf('File %s not found', $file));
				}

				$ret .= sprintf($mask, $file, crc32(file_get_contents($file)));
			}
		}
		else
		{
			foreach ($files as $file)
			{
				if (!file_exists($file))
				{
					throw new \LogicException(sprintf('File %s not found', $file));
				}

				$source = file_get_contents($file);
				$ret .= $source;
			}

			$ret = sprintf($mask, $ret);
		}

		return $ret;
	}
}



