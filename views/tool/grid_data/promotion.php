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
		
		$arrBranch = array(0=>'All');
		$arrItem = array();
		
		$strDate = date("Y-m-d");
		
		$sth = $this->db->prepare('SELECT * FROM promotion WHERE statid = 1');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$strStatus = ($strDate <= $arrRowData['end_date'])?'Active':'Inactive';
			
			if (!isset($arrItem[$arrRowData['item_id']])) {
				$arrItemDetails = $this->db->db_item($arrRowData['item_id']);
				$arrItem[$arrRowData['item_id']] = $arrItemDetails['name'];
			}
			
			if (!isset($arrBranch[$arrRowData['branch_id']])) {
				$arrItemDetails = $this->db->db_branch($arrRowData['branch_id']);
				$arrBranch[$arrRowData['branch_id']] = $arrItemDetails['name'];
			}
			
			$strItemTag = ($arrRowData['item_tag'])?$arrRowData['item_tag']:'All';
			$strItemDescription = ($arrRowData['item_id'])?$arrItem[$arrRowData['item_id']]:'All';
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrRowData['name'].'</cell>
				<cell>'.$strItemTag.'</cell>
				<cell>'.$strItemDescription.'</cell>
				<cell>'.$arrBranch[$arrRowData['branch_id']].'</cell>
				<cell>'.number_format($arrRowData['discount'],2).'</cell>
				<cell>'.$arrRowData['start_date'].'</cell>
				<cell>'.$arrRowData['end_date'].'</cell>
				<cell>'.$strStatus.'</cell>
			</row>';
	//	echo '<rows><row><cell>1</cell></row></rows>';
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();