<?php

class Branch extends Controller {

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
		$this->view->user_page = 't_branch';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('tool/js/branch_grid.js','tool/js/branch.js'));
		
		$this->view->render('tool/branch');
	}
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
	public function xhrBranchSaveAsNew() 
	{
	 	echo json_encode($this->model->xhr_branch_save_as_new($_POST));
	}
	
	public function xhrBranchUpdate() 
	{
	 	echo json_encode($this->model->xhr_branch_update($_POST));
	}
	
	public function xhrBranchDelete() 
	{
	 	echo json_encode($this->model->xhr_branch_delete($_POST));
	}
}