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
		$intBranch = $_GET['branch_id'];
		$strDate = $_GET['date'];
		$strViewType = $_GET['view_type'];
		
		$strCondition = '';
		$strConditionSerial = '';
		
		if ($intBranch) {
			$strCondition .= ' and a.branch_id = ' . $intBranch;
			$strConditionSerial .= ' and branch_id = ' . $intBranch;
		}
		
		switch ($strViewType) {
			case 1://Running Inventory
				$strCondition .= ' and a.statid = 1 and b.statid = 1';
				break;
			case 2://Ending Inventory
				$strCondition .= ' and a.statid = 1 and b.statid = 1';
				break;
			case 3://Serial List
				$strCondition .= ' and a.statid = 1 and b.statid = 1';
				break;
			default:
				//$strCondition .= ' and statid in (1,8)';
				break;
		}
		
		$arrBranch = array();
		$arrCategory = array();
		$arrSerial = array();
		
		
		$sth = $this->db->prepare('SELECT sum(a.receive_qty) as total_qty,a.branch_id,b.* FROM fifo as a left join item as b on a.item_id = b.id '.
			'WHERE 1 = 1 '.$strCondition . ' GROUP BY a.item_id,a.branch_id');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$intItem = $arrRowData['id'];
			if (!isset($arrCategory[$arrRowData['category_id']])) {
				$arrCategoryDetails = $this->db->db_item_category($arrRowData['category_id']);
				$arrCategory[$arrRowData['category_id']] = $arrCategoryDetails['name'];
			}
			
			if (!isset($arrBranch[$arrRowData['branch_id']])) {
				$arrBranchDetails = $this->db->db_branch($arrRowData['branch_id']);
				$arrBranch[$arrRowData['branch_id']] = $arrBranchDetails['name'];
			}
			$intBranch = $arrRowData['branch_id'];
			
			
			if ($strViewType == 1 OR $strViewType == 2 ) {
				$intPending = 0;
				$sthPendingDelivery = $this->db->prepare('SELECT sum(qty - qty_receive) as total_pending FROM delivery '.
					'WHERE statid = 6 and to_branch_id = '.$intBranch.' and item_id = '.$intItem);
				$sthPendingDelivery->execute();
				if ($arrRowPendingDeliveryData = $sthPendingDelivery->fetch()) {
					$intPending = $arrRowPendingDeliveryData['total_pending'];
				}
				
				$intTotal = $arrRowData['total_qty'] + $intPending;
				$strGrid .= '<row>
					<cell>'.$arrBranch[$intBranch].'</cell>
					<cell>'.$arrCategory[$arrRowData['category_id']].'</cell>
					<cell>'.$arrRowData['tag'].'</cell>
					<cell>'.$arrRowData['name'].'</cell>
					<cell>'.$intPending.'</cell>
					<cell>'.$arrRowData['total_qty'].'</cell>
					<cell>'.$intTotal.'</cell>
				</row>';
			} elseif ($strViewType == 3) {
				$sthSerial = $this->db->prepare('SELECT * FROM serial '.
					'WHERE statid = 1 and item_id = '.$intItem . $strConditionSerial );
				$sthSerial->execute();
				$blnNewItem = true;
				while ($arrRowDataSerial = $sthSerial->fetch()) {
					if ($blnNewItem) {
						$intQty = $arrRowData['total_qty'];
						$blnNewItem = false;
					} else {
						$intQty = '';
					}
					
					$strGrid .= '<row>
						<cell>'.$arrBranch[$intBranch].'</cell>
						<cell>'.$arrCategory[$arrRowData['category_id']].'</cell>
						<cell>'.$arrRowData['tag'].'</cell>
						<cell>'.$arrRowData['name'].'</cell>
						<cell>'.$intQty.'</cell>
						<cell>'.$arrRowDataSerial['imei'].'</cell>
					</row>';
				}
			}
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();