<?php

class Sale_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function branch_list() 
	{
		$sth = $this->db->prepare('SELECT * FROM branch WHERE statid = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	
}