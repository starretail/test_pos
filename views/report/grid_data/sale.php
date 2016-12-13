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
		$intBranch = $_GET['branch_id'];
		$strViewType = $_GET['view_type'];
		$strCondition = '';
		
		$strCondition .= ' and create_date >= "' . $strFromDate . '"
			and create_date <= "' .$strToDate. '"'; 
		if ($intBranch) {
			$strCondition .= ' and a.branch_id = ' . $intBranch;
		}
		
		switch ($strViewType) {
			case 1://Gross Sales
				//$strCondition .= '';
				break;
			case 2://Net Sales
				//$strCondition .= ' and statid = 1';
				break;
			default:
				//$strCondition .= ' and statid in (1,8)';
				break;
		}
		
		$arrBranchList = array();
		$arrEmployeeList = array();
		$arrItemList = array();
		$arrCategory = array();
		
		$sth = $this->db->prepare('SELECT a.*,b.id as sale_fifo_id,b.item_id,b.qty_sold,b.srp,b.discount_amount FROM '.
				'sale_hdr as a inner join sale_fifo as b on a.id = b.sale_hdr_id '.
			'WHERE  a.statid = 10 and b.statid = 10 '.$strCondition);
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$intSaleFifo = $arrRowData['sale_fifo_id'];
			$intBranch = $arrRowData['branch_id'];
			$intCreateBy = $arrRowData['create_by'];
			$intItem = $arrRowData['item_id'];
			
			if (!isset($arrBranchList[$intBranch])) {
				$arrBranchDetails = $this->db->db_branch($intBranch);
				$arrBranchList[$intBranch] = $arrBranchDetails['name'];
			}
			
			if (!isset($arrEmployeeList[$intCreateBy])) {
				$arrEmployeeDetails = $this->db->db_user_profile($intCreateBy);
				$arrEmployeeList[$intCreateBy] = $arrEmployeeDetails['first_name'] . ' ' . $arrEmployeeDetails['surname'];
			}
			
			if (!isset($arrItemList[$intItem])) {
				$arrItemeDetails = $this->db->db_item($intItem);
				$arrItemList[$intItem]['name'] = $arrItemeDetails['name'];
				$arrItemList[$intItem]['tag'] = $arrItemeDetails['name'];
				$arrItemList[$intItem]['category'] = $this->db->db_item_category($arrItemeDetails['category_id'],'name');
			}
			
			$strSerial = '';
			$arrSerialDetails = $this->db->db_serial_by_sale_fifo_id($intSaleFifo);
			$strSerial = ($arrSerialDetails)?$arrSerialDetails['imei']:'';
			$strGrid .= '<row>
				<cell>'.$arrRowData['create_date'].'</cell>
				<cell>'.$arrBranchList[$intBranch].'</cell>
				<cell>Sales</cell>
				<cell>'.$arrEmployeeList[$intCreateBy].'</cell>
				<cell>'.$arrRowData['invoice'].'</cell>
				<cell>'.$strSerial.'</cell>
				<cell>'.$arrItemList[$intItem]['category'].'</cell>
				<cell>'.$arrItemList[$intItem]['tag'].'</cell>
				<cell>'.$arrItemList[$intItem]['name'].'</cell>
				<cell>'.$arrRowData['qty_sold'].'</cell>
				<cell>'.$arrRowData['srp'].'</cell>
				<cell>'.$arrRowData['discount_amount'].'</cell>
				<cell>'.($arrRowData['srp'] - $arrRowData['discount_amount']).'</cell>
				<cell>'.(($arrRowData['srp'] - $arrRowData['discount_amount']) * $arrRowData['qty_sold']).'</cell>
			</row>';
		
		}
		
		if ($strViewType == 2) {//Net Sales
			$sth = $this->db->prepare('SELECT a.*,b.id as sale_return_fifo_id,b.item_id,b.qty_return,b.price FROM '.
					'sale_return_hdr as a inner join sale_return_fifo as b on a.id = b.sale_return_hdr_id '.
				'WHERE  a.statid = 14 and b.statid = 14 '.$strCondition);
			$sth->execute();
			while ($arrRowData = $sth->fetch()) {
				$intSaleReturnFifo = $arrRowData['sale_return_fifo_id'];
				$intBranch = $arrRowData['branch_id'];
				$intCreateBy = $arrRowData['create_by'];
				$intItem = $arrRowData['item_id'];
				
				if (!isset($arrBranchList[$intBranch])) {
					$arrBranchDetails = $this->db->db_branch($intBranch);
					$arrBranchList[$intBranch] = $arrBranchDetails['name'];
				}
				
				if (!isset($arrEmployeeList[$intCreateBy])) {
					$arrEmployeeDetails = $this->db->db_user_profile($intCreateBy);
					$arrEmployeeList[$intCreateBy] = $arrEmployeeDetails['first_name'] . ' ' . $arrEmployeeDetails['surname'];
				}
				
				if (!isset($arrItemList[$intItem])) {
					$arrItemeDetails = $this->db->db_item($intItem);
					$arrItemList[$intItem]['name'] = $arrItemeDetails['name'];
					$arrItemList[$intItem]['tag'] = $arrItemeDetails['name'];
					$arrItemList[$intItem]['category'] = $this->db->db_item_category($arrItemeDetails['category_id'],'name');
				}
				
				$strSerial = '';
				$arrSerialDetails = $this->db->db_serial_by_sale_return_fifo_id($intSaleReturnFifo);
				$strSerial = ($arrSerialDetails)?$arrSerialDetails['imei']:'';
				
				$arrSaleHdrDetails = $this->db->db_sale_hdr($arrRowData['sale_hdr_id']);
				
				$strGrid .= '<row style = "color:red;">
					<cell>'.$arrRowData['create_date'].'</cell>
					<cell>'.$arrBranchList[$intBranch].'</cell>
					<cell>Return</cell>
					<cell>'.$arrEmployeeList[$intCreateBy].'</cell>
					<cell>'.$arrSaleHdrDetails['invoice'].'</cell>
					<cell>'.$strSerial.'</cell>
					<cell>'.$arrItemList[$intItem]['category'].'</cell>
					<cell>'.$arrItemList[$intItem]['tag'].'</cell>
					<cell>'.$arrItemList[$intItem]['name'].'</cell>
					<cell>'.($arrRowData['qty_return'] * -1).'</cell>
					<cell>'.($arrRowData['price'] * -1).'</cell>
					<cell></cell>
					<cell>'.($arrRowData['price'] * -1).'</cell>
					<cell>'.($arrRowData['price'] * $arrRowData['qty_return'] * -1).'</cell>
				</row>';
			
			}
		}
			$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();