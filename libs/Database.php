<?php

class Database extends PDO
{
	
	public function __construct()
	{
		parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
	}
	
	public function sql_insert($strTable,$arrSqlData)
	{
		
		$strFieldName = '`' . implode('`,`',array_keys($arrSqlData)) . '`';
		$strFieldValue = ':' . implode(', :',array_keys($arrSqlData));
		
		$sth = $this->prepare("INSERT INTO $strTable ($strFieldName) VALUES ($strFieldValue)");
		
		foreach ($arrSqlData as $strKey => $strValue) {
			$sth->bindValue(":$strKey", $strValue);
		}
		$sth->execute(); 
		
		$intId = $this->lastInsertId();
		return $intId;
	}
	
	public function sql_update($strTable,$arrSqlDataUpdate,$strCondition)
	{
		
		$strFieldUpdate = '';
		foreach ($arrSqlDataUpdate as $strKey => $strValue) {
			$strFieldUpdate .= '`' . $strKey . '` = :' . $strKey . ',';
		}
		$strFieldUpdate = substr($strFieldUpdate,0,-1);
		
		$sth = $this->prepare("UPDATE $strTable SET $strFieldUpdate WHERE $strCondition");
		
		foreach ($arrSqlDataUpdate as $strKey => $strValue) {
			$sth->bindValue(":$strKey", $strValue);
		}
		
		
		$sth->execute(); 
		
		return;
	}
	
	public function db_branch($intId, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM branch WHERE id = ' .$intId);
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
		
	}
	
	public function db_item($intId, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM item WHERE id = ' .$intId);
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
		
	}
	
	public function db_item_category($intId, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM item_category WHERE id = ' .$intId);
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
		
	}
	
	public function db_payment_type($intId,$strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM payment_type WHERE id = ' .$intId);
		$sth->execute();
				
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
		
	}
	
	public function db_purchase_request_hdr($intId)
	{
		$sth = $this->prepare('SELECT * FROM purchase_request_hdr WHERE id = ' .$intId);
		$sth->execute();
		
		return $sth->fetch();
	}
	
	public function db_stock_location($intId)
	{
		$sth = $this->prepare('SELECT * FROM stock_location WHERE id = ' .$intId);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function db_supplier($intId)
	{
		$sth = $this->prepare('SELECT * FROM supplier WHERE id = ' .$intId);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function db_sale_hdr($intId,$strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM sale_hdr WHERE id = ' .$intId);
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
	}
	
	public function db_sale_fifo_by_sale_hdr_id($intSale)
	{
		$sth = $this->prepare('SELECT * FROM sale_fifo WHERE statid = 1 AND sale_hdr_id = ' .$intSale);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function db_sale_fifo($intSaleFifo)
	{
		$sth = $this->prepare('SELECT * FROM sale_fifo WHERE id = ' .$intSaleFifo);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function db_sale_payment_by_sale_hdr_id($intSale)
	{
		$sth = $this->prepare('SELECT * FROM sale_payment WHERE statid = 1 AND sale_hdr_id = ' .$intSale);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function db_sale_return_type_list()
	{
		$sth = $this->prepare('SELECT * FROM sale_return_type WHERE statid = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function db_serial_by_imei($strImei, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM serial WHERE statid != 2 and imei = "' .$strImei. '"');
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
	}
	
	public function db_fifo_qty_available($intItem, $intBranch, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT sum(receive_qty) as qty_available FROM fifo '.
			'WHERE item_id = "' .$intItem. '" and statid = 1 and branch_id = ' .  $intBranch);
		$sth->execute();
		
		return $sth->fetch();
	}
	
	public function db_delivery($intId, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM delivery WHERE id = "' .$intId. '"');
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
	}
	
	public function db_authorize_by_user_profile_id($intId, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM authorize WHERE user_profile_id = "' .$intId. '"');
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
	}
	
	public function db_user_profile($intId, $strColumn=NULL)
	{
		$sth = $this->prepare('SELECT * FROM user_profile WHERE id = "' .$intId. '"');
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
	}
	
	public function db_serial_by_sale_fifo_id($intId, $strColumn=NULL)
	{
		$arrRowData = array();
		$sth = $this->prepare('SELECT a.imei,b.* FROM serial as a inner join serial_history as b on a.id = b.serial_id '.
			'WHERE b.transaction_no = ' .$intId. ' and b.statid = 10');
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
	}
	
	public function db_serial_by_sale_return_fifo_id($intId, $strColumn=NULL)
	{
		$arrRowData = array();
		$sth = $this->prepare('SELECT a.imei,b.* FROM serial as a inner join serial_history as b on a.id = b.serial_id '.
			'WHERE b.transaction_no = ' .$intId. ' and b.statid = 14');
		$sth->execute();
		
		if ($strColumn) {
			$arrRowData = $sth->fetch();
			return $arrRowData[$strColumn];
		} else {
			return $sth->fetch();
		}
	}
	
	public function db_sale_return_hdr($intId)
	{
		$sth = $this->prepare('SELECT * FROM sale_return_hdr WHERE id = ' .$intId);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function db_replacement_credit($intId)
	{
		$sth = $this->prepare('SELECT * FROM replacement_credit WHERE id = ' .$intId);
		$sth->execute();
		return $sth->fetch();
	}
}	