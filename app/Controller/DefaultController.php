<?php

namespace Controller;

use \OSW3\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction()
	{
		$this->render('Default/index');
	}
}