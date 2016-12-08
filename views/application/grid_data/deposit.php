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
		
		$sth = $this->db->prepare('SELECT * FROM deposit WHERE statid = 1 and confirm=0');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrRowData['deposit_date'].'</cell>
				<cell>'.$arrRowData['account_no'].'</cell>
				<cell>'.$arrRowData['account_name'].'</cell>
				<cell>'.$arrRowData['amount'].'</cell>
				<cell>'.$arrRowData['deposited_by'].'</cell>				
			</row>';
		
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();