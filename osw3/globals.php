<?php

// Espace de nom global
namespace {

	/**
	 * print_r coké
	 * @param  mixed $var La variable a déboger
	 */
	function debug($var, $type = 'print')
	{
		$dbt = debug_backtrace();

		echo '<pre class="debug" style="padding: 10px; font-family: Consolas, Monospace; background-color: #000; color: #FFF;">';
		echo '<details style="display:inline-block;">';
		echo '<summary>'.print_r($var, true).'</summary>';
		echo 'File : '.$dbt[0]['file'].'<br>';
		echo 'Line : '.$dbt[0]['line'].'<br><br>';
  		echo '</details>';
		echo '</pre>';
	}



	/**
	 * Retourne l'instance de l'application depuis l'espace global
	 * @return \OSW3\App L'application
	 */
	function getApp()
	{
		if (!empty($GLOBALS['app'])){
			return $GLOBALS['app'];
		}

		return null;
	}

}