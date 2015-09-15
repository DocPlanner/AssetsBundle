<?php

namespace Docplanner\AssetsBundle\Service;

class AssetsLoader
{
	public function renderScript($type = null)
	{
		$file = 'file.js';
		$hash = 'xxx';

		if (null === $type)
		{
			return sprintf('<script src="%s?%s"></script>', $file, $hash);
		}
		else
		{
			if ('inline' == $type)
			{
				return sprintf('<script>%s</script>', $file);
			}
		}
	}
}



