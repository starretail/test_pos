<?php

class Controller {

	function __construct() {
		$this->view = new View();
	}
	
	public function loadModel($strGroup,$strController) {
		switch ($strGroup) {
			case 'a_':
				$strPath = 'models/application/'.$strController.'_model.php';
				break;
			case 'r_':
				$strPath = 'models/report/'.$strController.'_model.php';
				break;
			case 't_':
				$strPath = 'models/tool/'.$strController.'_model.php';
				break;
			case 'h_':
				$strPath = 'models/helper/'.$strController.'_model.php';
				break;
			default:
				$strPath = '';
				break;
		}
		
		if (file_exists($strPath)) {
			require $strPath;
			
			$modelName = $strController . '_Model';
			$this->model = new $modelName();
		}		
	}

}