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
		$arrPaymentTypeList = array();

		$sth = $this->db->prepare('SELECT a.*,b.id as sale_payment_id,b.payment_type_id,b.amount FROM '.
				'sale_hdr as a inner join sale_payment as b on a.id = b.sale_hdr_id'.
			'WHERE  a.statid = 10 and b.statid = 10 '.$strCondition);
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$intSalePayment = $arrRowData['sale_payment_id'];
			$intBranch = $arrRowData['branch_id'];
			$intCreateBy = $arrRowData['create_by'];
			$intPaymentType = $arrRowData['payment_type_id'];
			
			if (!isset($arrBranchList[$intBranch])) {
				$arrBranchDetails = $this->db->db_branch($intBranch);
				$arrBranchList[$intBranch] = $arrBranchDetails['name'];
			}
			
			if (!isset($arrEmployeeList[$intCreateBy])) {
				$arrEmployeeDetails = $this->db->db_user_profile($intCreateBy);
				$arrEmployeeList[$intCreateBy] = $arrEmployeeDetails['first_name'] . ' ' . $arrEmployeeDetails['surname'];
			}
			if (!isset($arrPaymentTypeList[$intPaymentType])) {
				$arrPaymentTypeDetails = $this->db->db_payment_type($intPaymentType);
				$arrPaymentTypeList[$intPaymentType] = $arrPaymentTypeDetails['name'];
			}
			
			$strGrid .= '<row>
				<cell>'.$arrRowData['create_date'].'</cell>
				<cell>'.$arrBranchList[$intBranch].'</cell>
				<cell>Sales</cell>
				<cell>'.$arrEmployeeList[$intCreateBy].'</cell>
				<cell>'.$arrRowData['invoice'].'</cell>
				<cell>'.$arrRowData['amount'].'</cell>
				<cell>'.$arrPaymentTypeList[$intPaymentType].'</cell>
			</row>';
		
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();