<?php

class Sell_Item_Return_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function xhr_sale_item_return_sales_no($arrPostData,$intBranch)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0,'finish' => 0);
		$strSalesNo = $arrPostData['txtSalesNo'];
		
		$sth = $this->db->prepare('SELECT * FROM sale_hdr '.
			'WHERE statid = 10 AND invoice = "'. $strSalesNo . '" and branch_id = ' . $intBranch);
		$sth->execute();
		$arrRowData = $sth->fetch();
		
		if ($arrRowData) {
			$intSale = $arrRowData['id'];
			$arrSellItemList = array();
			$sth = $this->db->prepare('SELECT * FROM sale_fifo '.
				'WHERE statid = 10 GROUP BY item_id');
			$sth->execute();
			while ($arrRowData = $sth->fetch()) {
					$arrRowData['item'] = $this->db->db_item($arrRowData['item_id'],'name');
					array_push($arrSellItemList,$arrRowData);
			}
			$arrResultData['sell_item_list'] = $arrSellItemList;
			$arrResultData['sale_id'] = $intSale;
			$arrResultData['valid'] = 1;
		}
		
		return $arrResultData;
	}
	
	public function xhr_sale_item_return_add_item($arrPostData,$intBranch)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0,'finish' => 0);
		
		if (!$arrPostData['selItemList']) {
			$strInfoMessage .= "Item is required. \n";
		}
		
		$intSaleReturn = $arrPostData['hidSaleReturnId'];
		$intSale = $arrPostData['hidSaleId'];
		
		if (!$arrPostData['txtQty']) {
			$strInfoMessage .= "Qty is required. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		
		$intItem = $arrPostData['selItemList'];
		$intQty = $arrPostData['txtQty'];
		
		$sth = $this->db->prepare('SELECT id,sum(qty_sold - qty_return) as qty_return_available FROM sale_fifo '.
			'WHERE statid = 10 and item_id = ' .$intItem. ' and sale_hdr_id = ' . $intSale);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$intQtyReturnAvailable = $arrRowData['qty_return_available'];
		$intSaleFifo = $arrRowData['id'];
		
		if ($intQtyReturnAvailable < $intQty) {
			$strInfoMessage .= "Qty to return is not available. \n";
		}
		
		$arrItemDetails = $this->db->db_item($intItem);
		$blnSerial = $arrItemDetails['serial'];
		
		if ($blnSerial) {
			if (!$arrPostData['txtaSerial']) {
				$strInfoMessage .= "Serial is required. \n";
			}
			
			if ($strInfoMessage) {
				$arrResultData['info_message'] = $strInfoMessage;
				return $arrResultData;
			}
			
			$strSerialList = $arrPostData['txtaSerial'];
			
			$arrCheckSerial = $this->check_serial_duplicate($strSerialList);
			$strInfoMessage .= $arrCheckSerial['info_message'];
			$arrSerialListInput = $arrCheckSerial['serial_list'];
			$intCount = $arrCheckSerial['qty'];
			$arrSerialList = array();
			foreach ($arrSerialListInput as $strSerial) {
				$sth = $this->db->prepare('SELECT a.id FROM sale_fifo as a inner join serial as b on a.id = b.transaction_no '.
					'WHERE a.statid = 10 and b.statid = 10 and b.branch_id = '.$intBranch.' and imei = "'.$strSerial.'" and a.sale_hdr_id = ' . $intSale);
				$sth->execute();
				$arrRowData = $sth->fetch();
				if (!$arrRowData) {
					$strInfoMessage .= "Imei [$strSerial] invalid. \n";
				} else {
					$arrSerialList[$arrRowData['id']] = $strSerial;
				}
				
				
			}
			
			if ($intCount != $intQty) {
				$strInfoMessage .= "Imei count does not match. \n";
			}
			
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		if (!$intSaleReturn) {
			$arrDataInsert = array(
				'sale_hdr_id' => $intSale,
				'create_date' => $strDate,
				'create_time' => $strTime,
				'create_by' => $intUser,
				'return_type_id' => 0,
				'branch_id' => $intBranch,
				'remarks' => "",
				'statid' => 13
			);
			$intSaleReturn = $this->db->sql_insert('sale_return_hdr',$arrDataInsert);
		}
		
		if ($blnSerial) {
			foreach ($arrSerialList as $intSaleFifo => $strSerial) {
				$sth = $this->db->prepare('SELECT * FROM sale_fifo as a inner join serial as b on a.id = b.transaction_no '.
					'WHERE a.statid = 10 and b.statid = 10 and b.branch_id = '.$intBranch.' and imei = "'.$strSerial.'" and a.sale_hdr_id = ' . $intSale);
				$sth->execute();
				$arrRowData = $sth->fetch();
				if (!$arrRowData) {
					$strInfoMessage .= "Imei [$strSerial] invalid. \n";
				}
					
				$arrDataUpdate = array(
					'statid' => 13
				);
				$strCondition = 'imei = "' . $strSerial . '"';
				$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
			}
			
			$arrDataUpdate = array(
				'qty_return' => 1
			);
			$strCondition = 'id = ' . $intSaleFifo;
			$this->db->sql_update('sale_fifo',$arrDataUpdate,$strCondition);
			
			$arrSaleFifoDetails = $this->db->db_sale_fifo($intSaleFifo);
			
			$arrDataInsert = array(
				'sale_return_hdr_id' => $intSaleReturn,
				'sale_hdr_id' => $intSale,
				'sale_fifo_id' => $intSaleFifo,
				'item_id' => $intItem,
				'qty_return' => 1,
				'price' => ($arrSaleFifoDetails['srp'] - $arrSaleFifoDetails['discount_amount']),
				'cost' => $arrSaleFifoDetails['cost'],
				'statid' => 13
			);
			$intSaleReturnFifo = $this->db->sql_insert('sale_return_fifo',$arrDataInsert);
		} else {
			$arrDataUpdate = array(
				'qty_return' => $intQty
			);
			$strCondition = 'id = ' . $intSaleFifo;
			$this->db->sql_update('sale_fifo',$arrDataUpdate,$strCondition);
			
			$arrSaleFifoDetails = $this->db->db_sale_fifo($intSaleFifo);
			
			$arrDataInsert = array(
				'sale_return_hdr_id' => $intSaleReturn,
				'sale_hdr_id' => $intSale,
				'sale_fifo_id' => $intSaleFifo,
				'item_id' => $intItem,
				'qty_return' => 1,
				'price' => ($arrSaleFifoDetails['srp'] - $arrSaleFifoDetails['discount_amount']),
				'cost' => $arrSaleFifoDetails['cost'],
				'statid' => 13
			);
			$intSaleReturnFifo = $this->db->sql_insert('sale_return_fifo',$arrDataInsert);
		}
		
		$arrResultData['valid'] = 1;
		
		return $arrResultData;
	}
	
	public function xhr_sale_item_return_refund($arrPostData,$intBranch)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0,'finish' => 0);
		
		if (!$arrPostData['hidSaleReturnId']) {
			$strInfoMessage .= "No details for refund. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intSaleReturn = $arrPostData['hidSaleReturnId'];
		
		$arrDataUpdate = array(
			'return_type_id' => 1
		);
		$strCondition = 'id = ' . $intSaleReturn;
		$this->db->sql_update('sale_return_hdr',$arrDataUpdate,$strCondition);
		
		$arrSaleReturnDetails = $this->db->db_sale_return_hdr($intSaleReturn);
		$intSale = $arrSaleReturnDetails['sale_hdr_id'];
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$sth = $this->db->prepare('SELECT * FROM sale_return_fifo '.
			'WHERE statid = 13 AND sale_return_hdr_id = '.$intSaleReturn);
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$intSaleReturnFifo = $arrRowData['id'];
			$intItem = $arrRowData['item_id'];
			$intSaleFifo = $arrRowData['sale_fifo_id'];
			$arrItemDetails = $this->db->db_item($intItem);
			$blnSerial = $arrItemDetails['serial'];
			$arrSaleFifoDetails = $this->db->db_sale_fifo($intSaleFifo);
			
			$arrDataInsert = array(
				'delivery_id' => $arrSaleFifoDetails['delivery_id'],
				'item_id' => $intItem,
				'transaction_qty' => $arrRowData['qty_return'],
				'transaction_date' => $strDate,
				'transaction' => $intSaleReturnFifo,
				'remarks' => 'From refund.',
				'cost' => $arrSaleFifoDetails['cost'],
				'sequence_no' => 0,
				'branch_id' => $intBranch,
				'statid' => 8
			);
			$intFifo = $this->db->sql_insert('fifo',$arrDataInsert);
			
			$arrDataInsert = array(
				'delivery_id' => $arrSaleFifoDetails['delivery_id'],
				'item_id' => $intItem,
				'receive_qty' => $arrRowData['qty_return'],
				'receive_date' => $strDate,
				'remarks' => 'From refund.',
				'cost' => $arrSaleFifoDetails['cost'],
				'sequence_no' => 1,
				'branch_id' => $intBranch,
				'statid' => 1
			);
			$intFifo = $this->db->sql_insert('fifo',$arrDataInsert);
			
			if ($blnSerial) {
				$sthSerial = $this->db->prepare('SELECT * FROM serial  '.
					'WHERE statid = 13 AND transaction_no = '.$intSaleFifo);
				$sthSerial->execute();
				$arrRowDataSerial = $sthSerial->fetch();
				$intSerial = $arrRowDataSerial['id'];
				
				$arrDataUpdate = array(
					'statid' => 1
				);
				$strCondition = 'id = ' . $intSerial;
				$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
			
				$arrDataInsert = array(
					'serial_id' => $intSerial,
					'branch_id' => $intBranch,
					'transaction_no' => $intSaleReturnFifo,
					'transaction_date' => $strDate,
					'remarks' => 'From refund.',
					'statid' => 14
				);
				$intSerialHistory = $this->db->sql_insert('serial_history',$arrDataInsert);
			}
		}
		
		$arrDataUpdate = array(
			'statid' => 14
		);
		$strCondition = 'id = ' . $intSaleReturn;
		$this->db->sql_update('sale_return_hdr',$arrDataUpdate,$strCondition);
		
		$arrDataUpdate = array(
			'statid' => 14
		);
		$strCondition = 'statid = 13 and sale_return_hdr_id = ' . $intSaleReturn;
		$this->db->sql_update('sale_return_fifo',$arrDataUpdate,$strCondition);
		
		$arrResultData['valid'] = 1;
		$arrResultData['finish'] = 1;
		$arrResultData['info_message'] = 'Refund complete' ;
		return $arrResultData;
	}
	
	public function xhr_sale_item_return_replacement($arrPostData,$intBranch)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0,'finish' => 0);
		
		if (!$arrPostData['hidSaleReturnId']) {
			$strInfoMessage .= "No details for refund. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intSaleReturn = $arrPostData['hidSaleReturnId'];
		
		$arrDataUpdate = array(
			'return_type_id' => 2
		);
		$strCondition = 'id = ' . $intSaleReturn;
		$this->db->sql_update('sale_return_hdr',$arrDataUpdate,$strCondition);
		
		$arrSaleReturnDetails = $this->db->db_sale_return_hdr($intSaleReturn);
		$intSale = $arrSaleReturnDetails['sale_hdr_id'];
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$fltTotaReplacement = 0;
		$sth = $this->db->prepare('SELECT * FROM sale_return_fifo '.
			'WHERE statid = 13 AND sale_return_hdr_id = '.$intSaleReturn);
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$intSaleReturnFifo = $arrRowData['id'];
			$intItem = $arrRowData['item_id'];
			$intSaleFifo = $arrRowData['sale_fifo_id'];
			$arrItemDetails = $this->db->db_item($intItem);
			$blnSerial = $arrItemDetails['serial'];
			$arrSaleFifoDetails = $this->db->db_sale_fifo($intSaleFifo);
			$fltTotaReplacement += $arrRowData['qty_return'] * $arrRowData['price'];
			
			$arrDataInsert = array(
				'delivery_id' => $arrSaleFifoDetails['delivery_id'],
				'item_id' => $intItem,
				'transaction_qty' => $arrRowData['qty_return'],
				'transaction_date' => $strDate,
				'transaction' => $intSaleReturnFifo,
				'remarks' => 'From refund.',
				'cost' => $arrSaleFifoDetails['cost'],
				'sequence_no' => 0,
				'branch_id' => $intBranch,
				'statid' => 8
			);
			$intFifo = $this->db->sql_insert('fifo',$arrDataInsert);
			
			$arrDataInsert = array(
				'delivery_id' => $arrSaleFifoDetails['delivery_id'],
				'item_id' => $intItem,
				'receive_qty' => $arrRowData['qty_return'],
				'receive_date' => $strDate,
				'remarks' => 'From refund.',
				'cost' => $arrSaleFifoDetails['cost'],
				'sequence_no' => 1,
				'branch_id' => $intBranch,
				'statid' => 1
			);
			$intFifo = $this->db->sql_insert('fifo',$arrDataInsert);
			
			if ($blnSerial) {
				$sthSerial = $this->db->prepare('SELECT * FROM serial  '.
					'WHERE statid = 13 AND transaction_no = '.$intSaleFifo);
				$sthSerial->execute();
				$arrRowDataSerial = $sthSerial->fetch();
				$intSerial = $arrRowDataSerial['id'];
				
				$arrDataUpdate = array(
					'statid' => 1
				);
				$strCondition = 'id = ' . $intSerial;
				$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
			
				$arrDataInsert = array(
					'serial_id' => $intSerial,
					'branch_id' => $intBranch,
					'transaction_no' => $intSaleReturnFifo,
					'transaction_date' => $strDate,
					'remarks' => 'From refund.',
					'statid' => 14
				);
				$intSerialHistory = $this->db->sql_insert('serial_history',$arrDataInsert);
			}
		}
		
		$arrDataUpdate = array(
			'statid' => 14
		);
		$strCondition = 'id = ' . $intSaleReturn;
		$this->db->sql_update('sale_return_hdr',$arrDataUpdate,$strCondition);
		
		$arrDataUpdate = array(
			'statid' => 14
		);
		$strCondition = 'statid = 13 and sale_return_hdr_id = ' . $intSaleReturn;
		$this->db->sql_update('sale_return_fifo',$arrDataUpdate,$strCondition);
		
		$arrDataInsert = array(
			'amount' => $fltTotaReplacement,
			'sale_return_hdr_id' => $intSaleReturn,
			'branch_id' => $intBranch,
			'statid' => 1
		);
		$intSerialHistory = $this->db->sql_insert('replacement_credit',$arrDataInsert);
		
		$arrResultData['valid'] = 1;
		$arrResultData['finish'] = 1;
		$arrResultData['info_message'] = 'Replacement complete';
		return $arrResultData;
	}
		
	public function item_list($intSale) 
	{
		$sth = $this->db->prepare('SELECT b.* FROM sale_fifo as a inner join item as b on a.item_id = b.id '.
			'WHERE a.statid = 10 AND a.sale_hdr_id = '.$intSale);
		$sth->execute();
		$arrRowData = $sth->fetchAll();
		return $arrRowData;
	}
	
	
	public function sell_item_return($intBranch) 
	{
		$sth = $this->db->prepare('SELECT a.*,b.invoice FROM sale_return_hdr as a inner join sale_hdr as b on a.sale_hdr_id = b.id '.
			'WHERE a.statid = 13 AND a.branch_id = ' . $intBranch);
		$sth->execute();
		
		return $sth->fetch();
	}
	
	public function sell_item_return_list($intSaleReturn) 
	{
		$sth = $this->db->prepare('SELECT item_id,price,sum(qty_return) AS qty_return FROM sale_return_fifo '.
			'WHERE statid = 13 AND sale_return_hdr_id = '. $intSaleReturn . 
			' GROUP BY item_id,price ORDER BY id');
		$sth->execute();
		$arrRowDataAll = array();
		while ($arrRowData = $sth->fetch()) {
			$arrRowData['item'] = $this->db->db_item($arrRowData['item_id'],'name');
			$arrRowDataAll[] = $arrRowData;
		}
		return $arrRowDataAll;
	}
	
	
	public function sell_item_return_payment_list($intSale) 
	{
		$sth = $this->db->prepare('SELECT * FROM sale_payment WHERE statid = 9 AND sale_hdr_id = '. $intSale . ' ORDER BY id');
		$sth->execute();
		$arrRowDataAll = array();
		while ($arrRowData = $sth->fetch()) {
			$arrRowData['payment_type'] = $this->db->db_payment_type($arrRowData['payment_type_id'],'name');
			$arrRowDataAll[] = $arrRowData;
		}
		return $arrRowDataAll;
	}
}