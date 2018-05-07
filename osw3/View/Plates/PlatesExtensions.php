<?php

namespace OSW3\View\Plates;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class PlatesExtensions implements ExtensionInterface
{
	/**
	 * Enregistre les nouvelles fonctions dans Plates
     * @param \League\Plates\Engine $engine L'instance du moteur de template
	 */
	public function register(Engine $engine)
	{
		$engine->registerFunction('asset', [$this, 'asset']);
		$engine->registerFunction('image', [$this, 'image']);
		$engine->registerFunction('css', [$this, 'css']);
		$engine->registerFunction('script', [$this, 'script']);
		$engine->registerFunction('url', [$this, 'url']);
		$engine->registerFunction('getCurrentRoute', [$this, 'getCurrentRoute']);
		$engine->registerFunction('getFlashbag', [$this, 'getFlashbag']);
		$engine->registerFunction('include', [$this, 'include']);
		$engine->registerFunction('getCurrentUser', [$this, 'getCurrentUser']);
		$engine->registerFunction('isGranted', [$this, 'isGranted']);
		$engine->registerFunction('jsRoutes', [$this, 'jsRoutes']);
	}


	/**
	 * Relative path of an asset
	 * @param string $file File name of the asset
	 * @return string Relative path of the asset
	 */
	public function asset($file)
	{
		return \OSW3\Controller\Controller::asset($file);
	}

	/**
	 * Relative path of an image
	 * @param string $file File name of the image
	 * @return string Relative path of the image
	 */
	public function image($file)
	{
		return \OSW3\Controller\Controller::image($file);
	}

	/**
	 * Relative path of a stylesheet
	 * @param string $file File name of the stylesheet
	 * @return string Relative path of the stylesheet
	 */
	public function css($file)
	{
		return \OSW3\Controller\Controller::css($file);
	}

	/**
	 * Relative path of a script
	 * @param string $file File name of the script
	 * @return string Relative path of the script
	 */
	public function script($file)
	{
		return \OSW3\Controller\Controller::script($file);
	}

	/**
	 * Generate URL by a route name
	 * @param string $route route name
	 * @param array $params Route parameters
	 * @param boolean $absolute Absolute URL if true
	 * @return string URL of the route
	 */
	public function url($route, $params=array(), $absolute=false)
	{
		return \OSW3\Controller\Controller::url($route, $params, $absolute);
	}

	/**
	 * Return Routes for JS
	 */
	public function jsRoutes($routeName=null, $routeNameAsRegex=false)
	{
		$app = getApp();
		$js_routes = [];

		if ($routeName) {
			foreach ($app->jsRoutes() as $route) {
				if ($route[0] === $routeName && !$routeNameAsRegex) {
					$js_routes[$route[0]] = $this->url($route[0]);
				}
				elseif (preg_match('/'.$routeName.'/', $route[0]) && $routeNameAsRegex) {
					$js_routes[$route[0]] = $this->url($route[0]);
				}
			}
		} else {
			foreach ($app->jsRoutes() as $route) {
				$js_routes[$route[0]] = $this->url($route[0]);
			}
		}

		return json_encode($js_routes);
	}

	/**
	 * Return current route name
	 * @return string Route name
	 */
	public function getCurrentRoute()
	{
		return \OSW3\Controller\Controller::getCurrentRoute();
	}

	/**
	 * Execute setFlashbag
	 */
	public function getFlashbag()
	{
		return \OSW3\Controller\Controller::getFlashbag();
	}

	/**
	 * 
	 */
	public function isGranted($roles)
	{
		$authorization = new \OSW3\Security\AuthorizationModel;
		return $authorization->isGranted($roles);
	}

	/**
	 * Include file
	 * @param string $file Path of the file you want to include. The file must be stocked in /app/Views/
	 * @param array $params Parameters you want to pass to the included file
	 * @param boolean $once default False if true this method use "include_once"
	 */
	public function include($file, $params=array(), $once=false)
	{
		$file = str_replace('.php', '', $file);
		$file = __DIR__."/../../../app/Views/".$file;
		$file.= '.php';

		foreach ($params as $key => $param) {
			$$key = $param;
		}

		if ($once) {
			include_once $file;
		} else {
			include $file;
		}
	}

	public function getCurrentUser($field=null)
	{
		return \OSW3\Controller\Controller::getCurrentUser($field);
	}
}
