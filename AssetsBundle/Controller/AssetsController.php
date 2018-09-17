<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/09/2018
 * Time: 16:50
 */

namespace Docplanner\AssetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetsController extends Controller
{
	public function scriptTagsAction(Request $request, $assetsType, $forRoute)
	{
		$scripts = [];

		$assetsLoader = $this->get('docplanner_assets_bundle.service.assets_loader');

		foreach($assetsLoader->assets($assetsType, false, $forRoute) as $script)
		{
			$scripts[] = sprintf('<script defer="defer" src="%s"></script>', $script);
		}

		return new Response(implode("\n", $scripts));
	}

}