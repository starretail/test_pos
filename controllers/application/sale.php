<?php

class Sale extends Controller {

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
		
	}
	
	public function index() 
	{
		$this->view->css = array('application/css/sale.css');
		$this->view->js = array('application/js/sale.js');
		
		if (isset($_REQUEST['tblData'])) {
			$this->model->sale_create($_REQUEST);
		}
		
		$this->view->item_list = $this->model->item_list();
		$this->view->pay_type_list = $this->model->pay_type_list();
		$this->view->customer_credit_list = $this->model->customer_credit_list();

		$this->view->render('application/sale');
		Session::set('message_info','');
	}
	
	public function xhrGetAvailableQtyByItemId() 
	{
	 	echo json_encode($this->model->xhr_get_available_qty_by_item_id($_POST));
	}
	
	public function xhrGetAvailableQtyByImei() 
	{
		echo json_encode($this->model->xhr_get_available_qty_by_imei($_POST));
	}
	
}