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
		$sth = $this->db->prepare('SELECT * FROM branch WHERE statid = 1');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$strActive = ($arrRowData['active'] == 1)?'Active':'Inactive';
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrRowData['name'].'</cell>
				<cell>'.$arrRowData['building_street'].'</cell>
				<cell>'.$arrRowData['barangay'].'</cell>
				<cell>'.$arrRowData['city_district'].'</cell>
				<cell>'.$arrRowData['province'].'</cell>
				<cell>'.$arrRowData['landline_no'].'</cell>
				<cell>'.$arrRowData['mobile_no'].'</cell>
				<cell>'.$arrRowData['date_active'].'</cell>
				<cell>'.$strActive.'</cell>
			</row>';
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();