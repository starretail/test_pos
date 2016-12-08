<?php

class Sell_Item_Return extends Controller {

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
		$this->view->user_page = 'a_sell_item_return';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('application/js/sell_item_return.js'));
		
		
		$intBranch = Session::get('branch_id');
		
		$this->view->item_list = '';
		$this->view->sell_item_return = $this->model->sell_item_return($intBranch);
		$this->view->sell_item_return_list = $this->model->sell_item_return_list($this->view->sell_item_return['id']);
		$this->view->item_list = $this->model->item_list($this->view->sell_item_return['sale_hdr_id']);
		
		if (isset($this->view->sell_item_return['proceed_to_payment']) and $this->view->sell_item_return['proceed_to_payment']) {
			$this->view->sell_item_return_payment_list = $this->model->sell_item_return_payment_list($this->view->sell_item_return['id']);
			$this->view->render('application/sell_item_return_proceed_to_payment');
		} else {
			$this->view->render('application/sell_item_return');
		}
		
	}
	
	public function xhrSaleItemReturnSalesNo() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sale_item_return_sales_no($_POST,$intBranch));
	}
	
	public function xhrSellItemReturnAddItem() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sale_item_return_add_item($_POST,$intBranch));
	}
	
	public function xhrSellItemReturnRefund() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sale_item_return_refund($_POST,$intBranch));
	}
	
	public function xhrSellItemReturnReplacement() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sale_item_return_replacement($_POST,$intBranch));
	}
	
	
}