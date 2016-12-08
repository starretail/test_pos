<?php

class Dashboard extends Controller {

	function __construct() {
		parent::__construct();
		Session::init();
		$logged = Session::get('loggedIn');
		if ($logged == false) {
			Session::destroy();
			header('location: '.URL.'h_login');
			exit;
		}
	
		$this->view->js = array('dashboard/js/default.js');
		
	}
	
	function index() 
	{	
		$this->view->render('dashboard/index');
	}
	
}