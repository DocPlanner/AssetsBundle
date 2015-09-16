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
		$files = 'file.js';

		$mask = $type == 'file' ? '<script>%s</script>' : '<script src="%s?%s"></script>';

		return $this->render($mask, $files, $type);
	}

	/**
	 * @param null $type
	 *
	 * @return string
	 */
	public function renderStyle($type = 'file')
	{
		$files = 'file.js';

		$mask = $type == 'file' ? '<style>%s</style>' : '<link rel="stylesheet" type="text/css" href="%s?%s">';

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
				$ret .= sprintf($mask, $file, crc32(file_get_contents($file)));
			}
		}
		else
		{
			foreach ($files as $file)
			{
				$source = file_get_contents($file);
				$ret .= $source;
			}

			$ret = sprintf($mask, $ret);
		}

		return $ret;
	}
}



