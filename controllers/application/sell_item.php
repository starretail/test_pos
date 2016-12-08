<?php

class Sell_Item extends Controller {

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
		$this->view->user_page = 'a_sell_item';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('application/js/sell_item.js'));
		
		$this->view->item_list = $this->model->item_list();
		
		$intBranch = Session::get('branch_id');
		
		$this->view->sell_item = $this->model->sell_item($intBranch);
		$this->view->sell_item_list = $this->model->sell_item_list($this->view->sell_item['id']);
		$this->view->sell_item_promo_list = $this->model->sell_item_promo_list($this->view->sell_item['id']);
		
		if (isset($this->view->sell_item['proceed_to_payment']) and $this->view->sell_item['proceed_to_payment']) {
			$this->view->payment_type_list = $this->model->payment_type_list();
			$this->view->sell_item_payment_list = $this->model->sell_item_payment_list($this->view->sell_item['id']);
			$this->view->render('application/sell_item_proceed_to_payment');
		} else {
			$this->view->render('application/sell_item');
		}
		
	}
	
	public function xhrGetAvailableQtyBySerialNo() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_get_available_qty_by_serial_no($_POST,$intBranch));
	}
	
	public function xhrGetAvailableQtyByItem() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_get_available_qty_by_item($_POST,$intBranch));
	}
	
	public function xhrSellItemAddItem() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sell_item_add_item($_POST,$intBranch));
	}
	
	public function xhrSellItemProceedToPayment() 
	{
	 	echo json_encode($this->model->xhr_sell_item_proceed_to_payment($_POST));
	}
	
	public function xhrSellItemAddPayment() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sell_item_add_payment($_POST,$intBranch));
	}
	
	public function xhrSellItemCancel() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sell_item_cancel($_POST,$intBranch));
	}
	
	public function xhrSellItemRemoveItem() 
	{
		$intBranch = Session::get('branch_id');
	 	echo json_encode($this->model->xhr_sell_item_remove_item($_POST,$intBranch));
	}
	
	public function xhrSellItemRemovePayment() 
	{
		echo json_encode($this->model->xhr_sell_item_remove_payment($_POST));
	}
	
	public function xhrSellItemPromoDiscount() 
	{
		echo json_encode($this->model->xhr_sell_item_promo_discount($_POST));
	}
	
	public function xhrSellItemReplacementCreditList() 
	{
		$intBranch = Session::get('branch_id');
		echo json_encode($this->model->xhr_sell_item_replacement_credit_list($_POST,$intBranch));
	}
	
	public function xhrSellItemReplacementCreditAmount() 
	{
		echo json_encode($this->model->xhr_sell_item_replacement_credit_amount($_POST));
	}
}