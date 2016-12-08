<?php

class User_Profile extends Controller {

	public function __construct() {
		parent::__construct();
		Session::init();
		$blnLogged = Session::get('loggedIn');
		$strRole = Session::get('role');
		
		$arrRoleAllow = array("owner","admin");
		if ($blnLogged == false ||  !in_array($strRole,$arrRoleAllow)){
			Session::destroy();
			header('location: '.URL.'helper/login');
			exit;
		}
		
		$this->view->css = array('tool/codebase/dhtmlxgrid.css');
		$this->view->js = array(
			'tool/codebase/dhtmlxgrid.js'
		);
		
		$this->view->user_role = $strRole;
		$this->view->user_page = 't_user_profile';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('tool/js/user_profile_grid.js','tool/js/user_profile.js'));
		
		$this->view->branch_list = $this->model->branch_list();
		$this->view->employee_position_list = $this->model->employee_position_list();
		
		$this->view->render('tool/user_profile');
	}
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
	public function xhrUserProfileSaveAsNew() 
	{
	 	echo json_encode($this->model->xhr_user_profile_save_as_new($_POST));
	}
	
	public function xhrUserProfileUpdate() 
	{
	 	echo json_encode($this->model->xhr_user_profile_update($_POST));
	}
	
	public function xhrUserProfileDelete() 
	{
	 	echo json_encode($this->model->xhr_user_profile_delete($_POST));
	}
	
	public function xhrUserProfilePasswordReset() 
	{
	 	echo json_encode($this->model->xhr_user_profile_password_reset($_POST));
	}
	
}