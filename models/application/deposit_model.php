<?php

class Deposit_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function deposit_update($arrDataUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('deposit',$arrDataUpdate,$strCondition);
	}

	public function xhr_deposit_save_as_new($arrPostData)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0);
		$strDepositDate = $arrPostData['txtDepositDate'];		
		$strAccountNo = $arrPostData['txtAccountNo'];
		$strAccountName = $arrPostData['txtAccountName'];
	    $strDepositAmount = $arrPostData['txtDepositAmount'];
		$strDepositedBy = $arrPostData['txtDepositedBy'];

		if (!$strDepositDate) {
			$strInfoMessage .= "Date of Deposit is required. \n";
		}
		
		if (!$strAccountNo) {
			$strInfoMessage .= "Account No is required. \n";
		}
		
		if (!$strAccountName) {
			$strInfoMessage .= "Account Name is required. \n";
		}

		if (!$strDepositAmount) {
			$strInfoMessage .= "Deposit Amount is required. \n";
		}

		if (!$strDepositedBy) {
			$strInfoMessage .= "Deposited By is required. \n";
		}
		
		
		$arrResultData['info_message'] = $strInfoMessage;
		if ($strInfoMessage) {
			return $arrResultData;
		}
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$arrDataInsert = array(
			'deposit_date' => $strDepositDate,
			'deposit_by' => $strDepositedBy,
			'account_no' => $strAccountNo,
			'account_name' => $strAccountName,
			'amount' => $strDepositAmount,
			'create_date' => $strDate,
			'create_time' => $strTime,
			'create_by' => $intUser,
			'statid' => 1,
			'confirm' => 0
		);
		$intDeposit = $this->db->sql_insert('deposit',$arrDataInsert);
			
		
		$arrResultData['valid'] = 1;
		$arrResultData['info_message'] = 'Deposit save1d!';
		return $arrResultData;
	}
	
	public function xhr_deposit_update($arrPostData)
	{
		$arrResultData = array('valid' => 0);
		$strInfoMessage = '';
		$strDepositDate = $arrPostData['txtDepositDate'];		
		
		
		if (!$arrPostData['hidDepositId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if (!$strDepositDate) {
			$strInfoMessage .= "Date of Deposit is required. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		
		}
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;

        $arrDataUpdate = array(
			'deposit_date' => $arrPostData['txtDepositDate'],
			'account_no' => $arrPostData['txtAccountNo'],
			'account_name' => $arrPostData['txtAccountName'],
			'amount' => $arrPostData['txtDepositAmount'],
			'deposited_by' => $arrPostData['txtDepositedBy']
		);
		
		$strCondition = 'id = '.$arrPostData['hidDepositId'];
		$this->db->sql_update('deposit',$arrDataUpdate,$strCondition);
			
		$arrResultData['valid'] = 1;
		$arrResultData['info_message'] = 'Update complete!';

		return $arrResultData;
	}
	
	public function xhr_deposit_confirm($arrPostData)
	{
		$arrResultData = array('valid' => 0);
		$strInfoMessage = '';
		
		if (!$arrPostData['hidDepositId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}

        $arrResultData['valid'] = 1;
		$arrResultData['info_message'] = 'Confirmed!';

		$arrDataUpdate = array(
				'confirm' => 1
		);
			
		$strCondition = 'id = '.$arrPostData['hidDepositId'];
		$this->db->sql_update('deposit',$arrDataUpdate,$strCondition);
			
		
		return $arrResultData;
	}
	
	public function xhr_deposit_cancel($arrPostData)
	{
		$arrResultData = array('valid' => 0);
		$strInfoMessage = '';
		
		if (!$arrPostData['hidDepositId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}

        $arrResultData['valid'] = 1;
		$arrResultData['info_message'] = 'Cancel deposit complete!';

		$arrDataUpdate = array(
				'statid' => 2
		);
			
		$strCondition = 'id = '.$arrPostData['hidDepositId'];
		$this->db->sql_update('deposit',$arrDataUpdate,$strCondition);
			
		
		return $arrResultData;
	}
	
	public function xhr_deposit_delete($arrPostData)
	{
		$arrResultData = array('valid' => 0);
		$strInfoMessage = '';
		
		if (!$arrPostData['hidDepositId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}

        $arrResultData['valid'] = 1;
		$arrResultData['info_message'] = 'Delete deposit complete!';

		$arrDataUpdate = array(
				'statid' => 3
		);
			
		$strCondition = 'id = '.$arrPostData['hidDepositId'];
		$sth = $this->db->sql_update('deposit',$arrDataUpdate,$strCondition);
			
		
		return $arrResultData;
	}
	
	public function xhr_get_data_grid_details($arrPostData)
	{
		$arrItemDetails = array();
		$intId = $arrPostData['data_id'];
		
		$sth = $this->db->prepare('SELECT * FROM deposit WHERE id = ' . $intId);
		$sth->execute();
		$arrRowData = $sth->fetch();
		return $arrRowData;
	}
}