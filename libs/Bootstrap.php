<?php

class Bootstrap {

	function __construct() {

		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url = explode('/', $url);

		if (empty($url[0])) {
			$strGroup = 'h_';
			$strController = 'login';
		} else {
			$strGroup = substr($url[0],0,2);
			$strController = substr($url[0],2);
		}

		$strPath = '';
		switch ($strGroup) {
			case 'a_':
				$strPath = 'controllers/application/' . $strController . '.php';
				break;
			case 'r_':
				$strPath = 'controllers/report/' . $strController . '.php';
				break;
			case 't_':
				$strPath = 'controllers/tool/' . $strController . '.php';
				break;
			case 'h_':
				$strPath = 'controllers/helper/' . $strController . '.php';
				break;
			default:
				$strGroup = 'h_';
				$strController = 'logout';
				$strPath = 'controllers/helper/' . $strController . '.php';
				break;
		}
		
		if (file_exists($strPath)) {
			require $strPath;
		} else {
			/*$strGroup = 'h_';
			$strController = 'logout';
			$strPath = 'controllers/helper/' . $strController . '.php';
			require $strPath;*/
			//$this->error();
		}
		
		$classController = new $strController;
		$classController->loadModel($strGroup,$strController);

		//var_dump($strGroup);
		//var_dump($strController);
		//die;
		if (isset($url[2])) {
			if (method_exists($classController, $url[1])) {
				$classController->{$url[1]}($url[2]);
			} else {
				//$this->error();
			}
		} else {
			if (isset($url[1])) {
				if (method_exists($classController, $url[1])) {
					$classController->{$url[1]}();
				} else {
					//$this->error();
					$classController->index();
				}
			} else {
				$classController->index();
			}
		}
		
		
	}
	
	function error() {
		//require 'controllers/error.php';
		//$classController = new Error();
		//$classController->index();
		return false;
	}

}