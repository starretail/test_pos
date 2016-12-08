<?php

class Helper_Model extends Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function login_run($arrData) 
	{
		$sth = $this->db->prepare("SELECT * FROM authorize WHERE 
				uname = :uname AND pword = MD5(:pword)");
		$sth->execute(array(
			':uname' => $arrData['login'],
			':pword' => $arrData['password']
		));
		
		$row = $sth->fetch();
		
		$count =  $sth->rowCount();
		if ($count > 0) {
			// login
			Session::init();
			Session::set('role', $row['role']);
			Session::set('user_id', $row['employee_id']);
			Session::set('loggedIn', true);
			header('location: ../dashboard');
		} else {
			$strMessageInfo = "Invalid username/password";
		}
		
		return $strMessageInfo;
	}

}