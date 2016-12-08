<?php

class View {

	function __construct() {
		//echo 'this is the view';
	}

	public function render($name, $noInclude = false)
	{
		Session::init();
		
		if ($noInclude == true) {
			require 'views/' . $name . '.php';	
		} else {
			switch (Session::get('role')) {
				case 'owner': 
					require 'views/header/owner.php';
					break;
				case 'admin': 
					require 'views/header/admin.php';
					break;
				case 'supervisor': 
					require 'views/header/supervisor.php';
					break;
				case 'staff': 
					require 'views/header/staff.php';
					break;
				default:
					require 'views/header/default.php';
					break;
			}
			
			require 'views/' . $name . '.php';
			require 'views/footer/footer.php';	
			
		}
	}
	
}