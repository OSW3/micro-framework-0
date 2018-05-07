<?php

namespace OSW3;

class App 
{
	protected $config;
	protected $router;
	protected $basePath;

	/**
	 * Constructeur
	 * @param array $routes Tableau de routes
	 * @param array $config Tableau optionnel de configurations
	 */
	public function __construct()
	{
		session_start();
		$this->setConfig();
		$this->routingSetup();

		if ($this->getConfig('mode') === 'dev') {
			error_reporting(E_ALL);
			ini_set("display_errors", 1);
		}
	}

	/**
	 * Configure le routage
	 * @param  array  $routes Tableau de routes
	 */
	private function routingSetup()
	{
		global $routes;
		$ar_routes = array();

		foreach ($routes as $route) {
			array_push($ar_routes, [
				isset($route[3]) ? $route[3] : "GET",
				isset($route[1]) ? $route[1] : null,
				isset($route[2]) ? $route[2] : null,
				isset($route[0]) ? $route[0] : null
			]);
		}

		$this->router = new \AltoRouter();

		//voir public/.htaccess
		//permet d'éviter une configuration désagréable (sous-dossier menant à l'appli)
		$this->basePath = (empty($_SERVER['BASE'])) ? '' : $_SERVER['BASE'];

		$this->router->setBasePath($this->basePath);
		$this->router->addRoutes($ar_routes);
	}

	/**
	 * Récupère les configurations fournies par l'appli
	 * @param array $config Tableau de configuration
	 */
	private function setConfig()
	{
		global $config;

		$defaultConfig = [
			'db_host' => 'localhost',
			'db_user' => 'root',
			'db_pass' => '',
			'db_name' => '',
			'db_table_prefix' => '',
			'mode' => 'dev',
			'security_user_table' => 'users',
			'security_id_property' => 'id',
			'security_username_property' => 'username',
			'security_email_property' => 'email',
			'security_password_property' => 'password',
			'security_roles_property' => 'role',
			'security_login_route_name' => 'login',
			'site_name'	=> '',
		];

		//remplace les configurations par défaut par celle de l'appli
		$this->config = array_merge($defaultConfig, $config);
	}


	/**
	 * Récupère une donnée de configuration
	 * @param   $key Le clef de configuration
	 * @return mixed La valeur de configuration
	 */
	public function getConfig($key)
	{
		return (isset($this->config[$key])) ? $this->config[$key] : null;
	}

	/**
	 * Exécute le routeur
	 */
	public function run()
	{
		$matcher = new \OSW3\Router\AltoRouter\Matcher($this->router);
		$matcher->match();
	}

	/**
	 * Retourne le routeur
	 * @return \AltoRouter Le routeur
	 */
	public function getRouter()
	{
		return $this->router;
	}

	public function jsRoutes()
	{
		global $routes;
		return $routes;
	}

	/**
	 * Retourne la base path
	 * @return  string La base path
	 */
	public function getBasePath()
	{
		return $this->basePath;
	}

	/**
	 * Retourne le nom de la route actuelle
	 * @return mixed Le nom de la route actuelle depuis \AltoRouter ou le false
	 */
	public function getCurrentRoute(){

		$route = $this->getRouter()->match();
		if($route){
			return $route['name'];
		}
		else {
			return false;
		}
	}
}