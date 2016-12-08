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
		
		$intFromBranch = $_GET['from_branch_id'];
		$intToBranch = $_GET['to_branch_id'];
		$strFromDate = $_GET['from_date'];
		$strToDate = $_GET['to_date'];
		$strViewType = $_GET['view_type'];
		
		$strCondition = '';
		
		if ($intFromBranch) {
			$strCondition .= ' and from_branch_id = ' . $intFromBranch;
		}
		
		if ($intToBranch) {
			$strCondition .= ' and to_branch_id = ' . $intToBranch;
		}
		
		if ($strFromDate) {
			$strCondition .= ' and create_date >= "' . $strFromDate . '"';
		}
		
		if ($strToDate) {
			$strCondition .= ' and create_date <= "' . $strToDate . '"';
		}
		
		switch ($strViewType) {
			case 1://Complete Receive
				$strCondition .= ' and statid = 8';
				break;
			case 2://Pending
				$strCondition .= ' and statid = 6';
				break;
			default:
				$strCondition .= ' and statid in (6,8)';
				break;
		}
		
		$sth = $this->db->prepare('SELECT * FROM delivery WHERE 1 = 1 '.$strCondition);
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			
			switch ($arrRowData['statid']) {
				case 6:
					$strStatus = 'Pending';
					break;
				case 8:
					$strStatus = 'Complete Receive';
					break;
			}
			
			if (!isset($arrItem[$arrRowData['item_id']])) {
				$arrItemDetails = $this->db->db_item($arrRowData['item_id']);
				$arrItem[$arrRowData['item_id']] = $arrItemDetails['name'];
			}
			
			if (!isset($arrBranch[$arrRowData['from_branch_id']])) {
				$arrBranchDetails = $this->db->db_branch($arrRowData['from_branch_id']);
				$arrBranch[$arrRowData['from_branch_id']] = $arrBranchDetails['name'];
			}
			if (!isset($arrBranch[$arrRowData['to_branch_id']])) {
				$arrBranchDetails = $this->db->db_branch($arrRowData['to_branch_id']);
				$arrBranch[$arrRowData['to_branch_id']] = $arrBranchDetails['name'];
			}
			
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrBranch[$arrRowData['from_branch_id']].'</cell>
				<cell>'.$arrBranch[$arrRowData['to_branch_id']].'</cell>
				<cell>'.$arrRowData['delivery_no'].'</cell>
				<cell>'.$arrRowData['delivery_date'].'</cell>
				<cell>'.$arrItem[$arrRowData['item_id']].'</cell>
				<cell>'.$arrRowData['qty'].'</cell>
				<cell>'.$arrRowData['qty_receive'].'</cell>
				<cell>'.$strStatus.'</cell>
			</row>';
		
		
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();