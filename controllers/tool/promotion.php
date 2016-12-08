<?php

class Promotion extends Controller {

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
		$this->view->user_page = 't_promotion';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
		
		$this->view->js = array_merge($this->view->js,
			array('tool/js/promotion_grid.js','tool/js/promotion.js'));
		$this->view->branch_list = $this->model->branch_list();
		$this->view->item_list = $this->model->item_list();
		$this->view->item_tag_list = $this->model->item_tag_list();
		
		$this->view->render('tool/promotion');
	}
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
	public function xhrPromotionSaveAsNew() 
	{
	 	echo json_encode($this->model->xhr_promotion_save_as_new($_POST));
	}
	
	public function xhrPromotionUpdate() 
	{
	 	echo json_encode($this->model->xhr_promotion_update($_POST));
	}
	
	public function xhrPromotionDelete() 
	{
	 	echo json_encode($this->model->xhr_promotion_delete($_POST));
	}
	
	public function xhrPromotionSelectItemTag() 
	{
	 	echo json_encode($this->model->xhr_promotion_select_item_tag($_POST));
	}
}