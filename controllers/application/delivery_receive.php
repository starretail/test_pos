<?php

class Delivery_Receive extends Controller {

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
		$this->view->user_page = 'a_delivery_receive';
		
	}
	
	public function index() 
	{	
	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('application/js/delivery_receive_grid.js','application/js/delivery_receive.js'));
		$strAction = (isset($_POST['subForm']))?$_POST['subForm']:"";
		switch($strAction) {
			case "Save as New":
				break;
			case "Update":
				$arrDataUpdate = array(
					'branch_id' => $_POST['selBranch'],
					'item_tag' => $_POST['txtItemTag'],
					'item_id' => $_POST['selItemList'],
					'name' => $_POST['txtPromoName'],
					'discount' => $_POST['txtDiscount'],
					'start_date' => $_POST['txtStartDate'],
					'end_date' => $_POST['txtEndDate']
				);
				$strCondition = 'id = ' . $_POST['hidDeliveryReceiveId'];
				$this->model->delivery_receive_update($arrDataUpdate,$strCondition);
				break;
		}
		
		$this->view->render('application/delivery_receive');
	}
	
	
	public function xhrDeliveryReceive() 
	{
	 	echo json_encode($this->model->xhr_delivery_receive($_POST));
	}
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
}