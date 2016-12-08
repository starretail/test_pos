<?php

class Delivery extends Controller {

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
		$this->view->user_page = 'a_delivery';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$strRole = Session::get('role');
		$intBranch = Session::get('branch_id');
		
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('application/js/delivery_grid.js','application/js/delivery.js'));
		
		switch ($strRole) {
			case 'staff':
			case 'supervisor':
				$this->view->from_branch = $intBranch;
				$arrBranchDetails = $this->model->db->db_branch($intUser);
				$this->view->from_branch_name = $arrBranchDetails['name'];
				$this->view->to_branch = '';
				break;
			case 'owner':
			case 'admin':
				$this->view->from_branch = '';
				$this->view->to_branch = '';
				break;
		}
		$this->view->branch_list = $this->model->branch_list();
		$this->view->item_list = $this->model->item_list();
		
		$this->view->render('application/delivery');
	}
	
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
	
	public function xhrDeliverySaveAsNew() 
	{
	 	echo json_encode($this->model->xhr_delivery_save_as_new($_POST));
	}
	
	
	public function xhrDeliveryCancel() 
	{
	 	echo json_encode($this->model->xhr_delivery_cancel($_POST));
	}
	
	
}