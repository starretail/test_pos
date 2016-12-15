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
		
		$strFromDate = $_GET['from_date'];
		$strToDate = $_GET['to_date'];
		$strViewType = $_GET['view_type'];
		
		$strCondition = '';
		
		if ($strFromDate) {
			$strCondition .= ' and create_date >= "' . $strFromDate . '"';
		}
		
		if ($strToDate) {
			$strCondition .= ' and create_date <= "' . $strToDate . '"';
		}
	
		switch ($strViewType) {
			case 1://Confirm
				$strCondition .= ' and confirm = 1';
				break;
			case 2://Pending
				$strCondition .= ' and confirm = 0';
				break;
			default:
				$strCondition .= ' and confirm in (1,0)';
				break;
		}
			
		$sth = $this->db->prepare('SELECT * FROM deposit WHERE statid = 1 '.$strCondition);
		$sth->execute();
		while($arrRowData =$sth->fetch()){
			
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrRowData['create_date'].'</cell>
				<cell>'.$arrRowData['account_no'].'</cell>
				<cell>'.$arrRowData['account_name'].'</cell>
				<cell>'.$arrRowData['amount'].'</cell>
				<cell>'.$arrRowData['deposit_by'].'</cell>
			</row>';
		
		}
		$strGrid .= '</rows>';
		
		
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();