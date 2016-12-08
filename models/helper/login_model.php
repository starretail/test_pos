<?php

class Login_Model extends Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function login_run($arrData) 
	{
		$sth = $this->db->prepare("SELECT * FROM authorize WHERE 
				uname = :uname AND pword = MD5(:pword) and statid = 1");
		$sth->execute(array(
			':uname' => $arrData['login'],
			':pword' => $arrData['password']
		));
		
		$row = $sth->fetch();
		
		$strMessageInfo = '';
		$count =  $sth->rowCount();
		if ($count > 0) {
			// login
			Session::init();
			Session::set('role', $row['role']);
			Session::set('user_id', $row['user_profile_id']);
			Session::set('branch_id', $row['branch_id']);
			Session::set('loggedIn', true);
			switch ($row['role']) {
				case 'owner':
					header('location: '.URL.'t_branch');
					break;
				case 'supervisor':
					header('location: '.URL.'a_delivery_receive');
					break;
				case 'staff':
					header('location: '.URL.'a_delivery_receive');
					break;
			}
		} else {
			$strMessageInfo = "Invalid username/password";
		}
		
		return $strMessageInfo;
	}

}