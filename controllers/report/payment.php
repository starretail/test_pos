<?php

class Payment extends Controller {

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
		
		$this->view->css = array('report/codebase/dhtmlxgrid.css');
		$this->view->js = array('report/codebase/dhtmlxgrid.js');
		
		$this->view->user_role = $strRole;
		$this->view->user_page = 'r_payment';
			
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->branch_list = $this->model->branch_list();
		
		$strRole = Session::get('role');
		switch ($strRole) {
			case 'supervisor':
			case 'staff':
				$this->view->js = array_merge($this->view->js,
					array('report/js/payment_grid.js','report/js/payment_branch.js'));
				$this->view->branch_id = Session::get('branch_id');
				$this->view->render('report/payment_branch');
				break;
			case 'owner':
			case 'admin':
				$this->view->js = array_merge($this->view->js,
					array('report/js/payment_grid.js','report/js/payment.js'));
				$this->view->render('report/payment');
				break;
		}
	}
	
}