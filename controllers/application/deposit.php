<?php

class Deposit extends Controller {

	public function __construct() {
		parent::__construct();
		Session::init();
		$blnLogged = Session::get('loggedIn');
		$strRole = Session::get('role');
		
		$arrRoleAllow = array("owner","admin",'supervisor',"staff");
		if ($blnLogged == false ||  !in_array($strRole,$arrRoleAllow)){
			Session::destroy();
			header('location: '.URL.'helper/login');
			exit;
		}
		
		$this->view->css = array('application/codebase/dhtmlxgrid.css');
		$this->view->js = array('application/codebase/dhtmlxgrid.js');
		
		$this->view->user_role = $strRole;
		$this->view->user_page = 'a_deposit';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$strRole = Session::get('role');
		$intBranch = Session::get('branch_id');
		
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		$this->view->js = array_merge($this->view->js,
			array('application/js/deposit_grid.js','application/js/deposit.js'));
		
		
		$this->view->render('application/deposit');
	}
	
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
	
	public function xhrDepositSaveAsNew() 
	{
	 	echo json_encode($this->model->xhr_deposit_save_as_new($_POST));
	}
	
	public function xhrDepositUpdate() 
	{
	 	echo json_encode($this->model->xhr_deposit_update($_POST));
	}
	
	public function xhrDepositCancel() 
	{
	 	echo json_encode($this->model->xhr_deposit_cancel($_POST));
	}
	
	public function xhrDepositConfirm() 
	{
	 	echo json_encode($this->model->xhr_deposit_confirm($_POST));
	}
	public function xhrDepositDelete() 
	{
	 	echo json_encode($this->model->xhr_deposit_delete($_POST));
	}
	
	
	
}