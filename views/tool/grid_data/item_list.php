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
		
		$arrItemCategory = array();
		$sth = $this->db->prepare('SELECT * FROM item_category WHERE statid = 1');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$arrItemCategory[$arrRowData['id']] = $arrRowData['name']; 
		}
		
		$sth = $this->db->prepare('SELECT * FROM item WHERE statid = 1');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			//$strStatus = ($arrRowData['active'] == 1)?'Active':'Inactive';
			$strSerial = ($arrRowData['serial'])?'Yes':'No';
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrRowData['name'].'</cell>
				<cell>'.$arrRowData['tag'].'</cell>
				<cell>'.$arrItemCategory[$arrRowData['category_id']].'</cell>
				<cell>'.number_format($arrRowData['srp'],2).'</cell>
				<cell>'.number_format($arrRowData['dp'],2).'</cell>
				<cell>'.$strSerial.'</cell>
			</row>';
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();