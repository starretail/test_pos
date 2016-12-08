<?php

class Sale_Return extends Controller {

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
		$this->view->css = array('tool/codebase/dhtmlxgrid.css');
		$this->view->js = array('tool/codebase/dhtmlxgrid.js','application/js/sale_return.js');

		$this->view->js = array_merge($this->view->js,array('application/js/sale_grid.js'));
		$this->view->stock_location_list = $this->model->stock_location_list();
		$this->view->render('application/sale_return');	
		
		Session::set('message_info','');
	}
	
	public function sale_return_detail($intSale) 
	{
		$this->view->js = array('application/js/sale_return_detail.js');
		
		$this->view->sale_return_type_list = $this->model->sale_return_type_list();
		$strAction = (isset($_POST['subForm']))?$_POST['subForm']:"";
		switch($strAction) {
			case "Return Transaction":
				$this->model->sale_return_transaction($intSale);
				header('Location: ' . URL . 'a_sale_return');
				break;
			case "Back":
				header('Location: ' . URL . 'a_sale_return');
				break;
		}
		
		$this->view->sale_hdr = $this->model->db->db_sale_hdr($intSale);
		$this->view->sale_fifo = $this->model->sale_item_details($intSale);
		$this->view->sale_payment = $this->model->sale_payment_details($intSale);
		
		$this->view->render('application/sale_return_detail');	
		
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