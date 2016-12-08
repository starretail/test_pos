<?php

class Login extends Controller {

	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$strMessageInfo = "";
		$strAction = (isset($_POST['subForm']))?$_POST['subForm']:"";
		switch($strAction) {
			case "Login":
				$arrData = array();
				$arrData['login'] = $_POST['txtLogin'];
				$arrData['password'] = $_POST['txtPassword'];
				$strMessageInfo = $this->model->login_run($arrData);
				break;
		}
		
		$this->view->message_info = $strMessageInfo;
		$this->view->render('helper/login');
	}
	
	
}