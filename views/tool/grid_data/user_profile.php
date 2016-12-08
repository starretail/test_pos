<?php
require_once '../../../config/paths.php';
require_once '../../../config/database.php';
require_once '../../../libs/Database.php';

class Grid_Data {
	function __construct() {
		$this->db = new Database();
	}
	
	public function index() {
		$strGrid = '<?xml version="1.0" encoding="UTF-8"?>';
		$strGrid .= '<rows>';
		
		$arrEmployeePositionDetails = array();
		$sth = $this->db->prepare('SELECT * FROM employee_position WHERE statid = 1');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$arrEmployeePositionDetails[$arrRowData['id']]['name'] = $arrRowData['name']; 
		}
		
		$arrBranchDetails = array();
		
		$sth = $this->db->prepare('SELECT * FROM branch');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$arrBranchDetails[$arrRowData['id']]['name'] = $arrRowData['name']; 
			$arrBranchDetails[$arrRowData['id']]['address'] = $arrRowData['building_street'] .' '. 
				$arrRowData['barangay'] .' '. $arrRowData['city_district']  .' '. $arrRowData['province'] ; 
			$arrBranchDetails[$arrRowData['id']]['landline_no'] = $arrRowData['landline_no']; 
			$arrBranchDetails[$arrRowData['id']]['mobile_no'] = $arrRowData['mobile_no']; 
		}
		
		$sth = $this->db->prepare('SELECT * FROM user_profile WHERE statid = 1');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$intAge = 0;
			$strStatus = ($arrRowData['active'] == 1)?'Active':'Inactive';
			$strUserName = $this->db->db_authorize_by_user_profile_id($arrRowData['id'],'uname');
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrRowData['surname'].'</cell>
				<cell>'.$arrRowData['first_name'].'</cell>
				<cell>'.$arrRowData['middle_name'].'</cell>
				<cell>'.$arrEmployeePositionDetails[$arrRowData['employee_position_id']]['name'].'</cell>
				<cell>'.$strUserName.'</cell>
				<cell>'.$arrBranchDetails[$arrRowData['branch_id']]['name'].'</cell>
				<cell>'.$arrBranchDetails[$arrRowData['branch_id']]['address'].'</cell>
				<cell>'.$arrBranchDetails[$arrRowData['branch_id']]['landline_no'].'</cell>
				<cell>'.$arrBranchDetails[$arrRowData['branch_id']]['mobile_no'].'</cell>
				<cell>'.$arrRowData['birthday'].'</cell>
				<cell>'.$arrRowData['address'].'</cell>
				<cell>'.$arrRowData['contact_no'].'</cell>
				<cell>'.$arrRowData['date_hired'].'</cell>
				<cell>'.$strStatus.'</cell>
			</row>';
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();