<?php

class Item_List extends Controller {

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
		$this->view->js = array('tool/codebase/dhtmlxgrid.js');
		
		$this->view->user_role = $strRole;
		$this->view->user_page = 't_item_list';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('tool/js/item_list_grid.js','tool/js/item_list.js'));
		
		$this->view->item_category_list = $this->model->item_category_list();
		
		$this->view->render('tool/item_list');
	}
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
	public function xhrItemListSaveAsNew() 
	{
	 	echo json_encode($this->model->xhr_item_list_save_as_new($_POST));
	}
	
	public function xhrItemListUpdate() 
	{
	 	echo json_encode($this->model->xhr_item_list_update($_POST));
	}
	
	public function xhrItemListDelete() 
	{
	 	echo json_encode($this->model->xhr_item_list_delete($_POST));
	}
	
}