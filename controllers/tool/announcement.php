<?php

class Announcement extends Controller {
	/*checked if the user is logged in*/
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
		
		$this->view->css = array('tool/codebase/dhtmlxgrid.css');
		$this->view->js = array('tool/codebase/dhtmlxgrid.js');
		
		$this->view->user_role = $strRole;
		$this->view->user_page = 't_announcement';
		
	}
	
	public function index() 
	{	
		$intUser = Session::get('user_id');
		$arrUserProfile = $this->model->db->db_user_profile($intUser);
		$this->view->user_fullname = $arrUserProfile['first_name'] . ' ' . $arrUserProfile['surname'];
	
		$this->view->js = array_merge($this->view->js,
			array('tool/js/announcement_grid.js','tool/js/announcement.js'));
		$strAction = (isset($_POST['subForm']))?$_POST['subForm']:"";
		
		$strRole = Session::get('role');
		switch ($strRole) {
			case 'supervisor':
			case 'staff':
				$this->view->js = array_merge($this->view->js,
					array('tool/js/announcement_grid.js','tool/js/announcement_branch.js'));
			
				//$this->view->branch_id = Session::get('branch_id');
				$this->view->render('tool/announcement_branch');
				break;
			case 'owner':
			case 'admin':
				$this->view->js = array_merge($this->view->js,
					array('tool/js/announcement_grid.js','report/js/announcement.js'));
				$this->view->render('tool/announcement');
				break;
		}
		
		
	}
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
	public function xhrAnnouncementSaveAsNew() 
	{
	 	echo json_encode($this->model->xhr_announcement_save_as_new($_POST));
	}
	
	public function xhrAnnouncementUpdate() 
	{
	 	echo json_encode($this->model->xhr_announcement_update($_POST));
	}
	
	public function xhrAnnouncementDelete() 
	{
	 	echo json_encode($this->model->xhr_announcement_delete($_POST));
	}
}