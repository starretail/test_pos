<?php

class Change_Password extends Controller {

	public function __construct() {
		parent::__construct();
		Session::init();
		$blnLogged = Session::get('loggedIn');
		$strRole = Session::get('role');
		
		$arrRoleAllow = array("owner","admin",'supervisor','staff');
		if ($blnLogged == false ||  !in_array($strRole,$arrRoleAllow)){
			Session::destroy();
			header('location: '.URL.'helper/login');
			exit;
		}
		
		$this->view->css = array();
		$this->view->js = array();
		
		$this->view->user_role = $strRole;
		$this->view->user_page = 'h_change_password';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$arrAuthorize = $this->model->db->db_authorize_by_user_profile_id($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('helper/js/change_password.js'));
		
		$this->view->user_profile_id = $intUser;
		$this->view->user_name = $arrAuthorize['uname'];
		
		$this->view->render('helper/change_password');
	}
	
	public function xhrChangePassword() 
	{
	 	echo json_encode($this->model->xhr_change_password($_POST));
	}
	
}