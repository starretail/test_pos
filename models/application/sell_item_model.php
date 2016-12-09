<?php

class Sell_Item_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function xhr_get_available_qty_by_serial_no($arrPostData,$intBranch)
	{
		$strSerialNo = $arrPostData['txtSerialNo'];
		$arrResultData = array('valid' => 0,'finish' => 0);
		$sth = $this->db->prepare('SELECT * FROM serial '.
			'WHERE statid = 1 and imei = "' . $strSerialNo . '" and branch_id = ' . $intBranch);
		$sth->execute();
		$arrRowData = $sth->fetch();
		
		$strDate = date("Y-m-d");
		
		if ($arrRowData) {
			$intItem = $arrRowData['item_id'];
			
			$arrItemDetails = $this->db->db_item($intItem);
			$strItemTag = $arrItemDetails['tag'];
			
			$arrResultData['valid'] = 1;
			$arrResultData['item_id'] = $intItem;
			$arrResultData['selling_price'] = $arrItemDetails['srp'];
			$arrResultData['net_sales'] = $arrItemDetails['srp'];
		
			$arrPromoList = array();
			$sthPromo = $this->db->prepare('SELECT * FROM promotion '.
				'WHERE statid = 1 AND active = 1 AND (branch_id = 0 OR branch_id = '.$intBranch.') AND '.
					'(item_id = 0 OR item_id = '.$intItem.') AND (item_tag = "" OR item_tag = "'.$strItemTag.'" ) AND '.
					'start_date <= "' .$strDate. '" AND end_date >= "' .$strDate. '"');
			$sthPromo->execute();
			while ($arrRowDataPromo = $sthPromo->fetch()) {
					array_push($arrPromoList,$arrRowDataPromo);
			}
			$arrResultData['promo_list'] = $arrPromoList;
		}
		return $arrResultData;
	}
	
	public function xhr_get_available_qty_by_item($arrPostData,$intBranch)
	{
		$arrResultData = array('valid' => 0);
		$intItem = $arrPostData['selItemList'];
		
		$arrItemDetails = $this->db->db_item($intItem);
		$arrResultData['serial'] = $arrItemDetails['serial'];
		$arrResultData['invoice'] = $arrItemDetails['invoice'];
		$strItemTag = $arrItemDetails['tag'];
		$arrResultData['item_id'] = $intItem;
		$arrResultData['selling_price'] = $arrItemDetails['srp'];
		$arrResultData['net_sales'] = $arrItemDetails['srp'];
		
		if (!$arrItemDetails['serial']) {
			$arrResultData['valid'] = 1;
			$arrPromoList = array();
			$sthPromo = $this->db->prepare('SELECT * FROM promotion '.
				'WHERE statid = 1 AND active = 1 AND (branch_id = 0 OR branch_id = '.$intBranch.') AND '.
					'(item_id = 0 OR item_id = '.$intItem.') AND (item_tag = "" OR item_tag = "'.$strItemTag.'" ) AND '.
					'start_date <= "' .$strDate. '" AND end_date >= "' .$strDate. '"');
			$sthPromo->execute();
			while ($arrRowDataPromo = $sthPromo->fetch()) {
					array_push($arrPromoList,$arrRowDataPromo);
			}
			$arrResultData['promo_list'] = $arrPromoList;
		}
		
		return $arrResultData;
	}
	
	public function xhr_sell_item_add_item($arrPostData,$intBranch)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0,'finish' => 0);
		
		if (!$arrPostData['txtDate']) {
			$strInfoMessage .= "Date is required. \n";
		}
		
		if (!$arrPostData['txtSalesNo']) {
			$strInfoMessage .= "Sales number is required. \n";
		}
		
		if ($arrPostData['txtSerialNo'] == 'Serial needed for this item.') {
			$strInfoMessage .= "Serial number is required. \n";
		}
		
		if (!$arrPostData['selItemList']) {
			$strInfoMessage .= "Item is required. \n";
		}
		
		if (!$arrPostData['txtQty']) {
			$strInfoMessage .= "Quantity is required. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intItem = $arrPostData['selItemList'];
		$fltSrp = $arrPostData['txtSellingPrice'];
		$intQty = ($arrPostData['txtSerialNo'])?1:$arrPostData['txtQty'];
		$arrResultData = $this->db->db_fifo_qty_available($intItem,$intBranch);
		
		if ($arrResultData['qty_available'] < $intQty) {
			$strInfoMessage .= "Quantiy to sell is not available. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
	
		$strInvoice = $arrPostData['txtSalesNo'];
		$sthInvoice = $this->db->prepare('SELECT * FROM sale_hdr '.
			'WHERE statid in (10,11) and invoice = "'.$strInvoice . '"');
		$sthInvoice->execute();
		if ($sthInvoice->fetch()) {
		 	$strInfoMessage .="Sales number already exist.\n";
		}
		if ($strInfoMessage){
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
	
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		if ($arrPostData['hidSaleId']) {
			$intSale = $arrPostData['hidSaleId'];
		} else {
			$arrDataInsert = array(
				'invoice' => $arrPostData['txtSalesNo'],
				'create_date' => $arrPostData['txtDate'],
				'create_by' => $intUser,
				'branch_id' => $intBranch,
				'statid' => 9
			);
			$intSale = $this->db->sql_insert('sale_hdr',$arrDataInsert);
		}
		
		$intTempQty = $intQty;
		do {
			$sth = $this->db->prepare('SELECT * FROM fifo '.
				'WHERE item_id = ' . $intItem . ' and statid = 1 and branch_id = ' . $intBranch . 
				' ORDER BY delivery_id,sequence_no');
			$sth->execute();
			$arrRowData = $sth->fetch();
			if (!$arrRowData) {
				$arrResultData['info_message'] = 'No available qty.';
				return $arrResultData;
			}
			$intFifo = $arrRowData['id'];
			$intDelivery = $arrRowData['delivery_id'];
			$fltCost = $arrRowData['cost'];
			$intFifoQty = $arrRowData['receive_qty'];
			$strRemarks = $arrRowData['remarks'];
			$intSequence = $arrRowData['sequence_no'];
			
			
			if ($intFifoQty > $intTempQty) {
				$arrDataInsert = array(
					'delivery_id' => $intDelivery,
					'item_id' => $intItem,
					'receive_qty' => ($intFifoQty - $intTempQty),
					'receive_date' => $strToday,
					'remarks' => 'After sell item.',
					'cost' => $fltCost,
					'sequence_no' => ($intSequence + 1),
					'branch_id' => $intBranch,
					'statid' => 1
				);
				$this->db->sql_insert('fifo',$arrDataInsert);
				
				$intUsedQty = $intTempQty;
				$intTempQty = 0;
			} else {
				$intUsedQty = $intFifoQty;
				$intTempQty = $intTempQty - $intFifoQty;
			}
			
			$arrDataUpdate = array(
				'transaction_qty' => $intUsedQty,
				'transaction_date' => $strToday,
				'transaction' => $intSale,
				'remarks' => $strRemarks . 'After sell item.',
				'statid' => 10
			);
			$strCondition = 'id = ' . $intFifo;
			$this->db->sql_update('fifo',$arrDataUpdate,$strCondition);
				
			$fltProfit = ($fltSrp - $fltCost) * $intUsedQty;
			$arrDataInsert = array(
				'sale_hdr_id' => $intSale,
				'qty_sold' => $intUsedQty,
				'srp' => $fltSrp,
				'cost' => $fltCost,
				'profit' => $fltProfit,
				'fifo_id' => $intFifo,
				'delivery_id' => $intDelivery,
				'item_id' => $intItem,
				'promotion_id' => $arrPostData['selPromoList'],
				'discount_amount' => $arrPostData['txtDiscountAmount'],
				'statid' => 9
			);
			$intSaleFifo = $this->db->sql_insert('sale_fifo',$arrDataInsert);
			
			if ($arrPostData['txtSerialNo']) {
				$strSerial = $arrPostData['txtSerialNo'];
				$arrDataUpdate = array(
					'transaction_no' => $intSaleFifo,
					'statid' => 9
				);
				$strCondition = "imei = '$strSerial' and statid  = 1 and branch_id = $intBranch";
				$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
			}
		} while ($intTempQty > 0);
		
		$arrResultData['sale_hdr_id'] = $intSale;
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_sell_item_proceed_to_payment($arrPostData)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0,'finish' => 0);
		
		if (!$arrPostData['hidSaleId']) {
			$strInfoMessage .= "Please add an item. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intSale = $arrPostData['hidSaleId'];
		$arrDataUpdate = array(
			'proceed_to_payment' => 1
		);
		$strCondition = "id = $intSale";
		$this->db->sql_update('sale_hdr',$arrDataUpdate,$strCondition);
		
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_sell_item_add_payment($arrPostData,$intBranch)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0,'finish' => 0);
		
		if (!$arrPostData['selPaymentType']) {
			$strInfoMessage .= "Payment type is required. \n";
		}
		
		if (!$arrPostData['txtCustomer']) {
			$strInfoMessage .= "Customer is required. \n";
		}
		
		if (!$arrPostData['txtAmountPaid']) {
			$strInfoMessage .= "Amount paid is required. \n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intPaymentType = $arrPostData['selPaymentType'];
		$arrPaymentType = $this->db->db_payment_type($intPaymentType);
		if ($arrPaymentType['credit_card'] == 1) {
			if (!$arrPostData['txtCardNo']) {
				$strInfoMessage .= "Card number is required. \n";
			}
		}
				
		
		$fltAmount = $arrPostData['txtAmountPaid'];
		$strCustomer = $arrPostData['txtCustomer'];
		$strCardNo = $arrPostData['txtCardNo'];
		
		$intSale = $arrPostData['hidSaleId'];
		
		$sth = $this->db->prepare('SELECT sum((srp -  discount_amount) * qty_sold)  as total_amount FROM sale_fifo '.
			'WHERE statid = 9 AND sale_hdr_id = ' . $intSale);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$fltTotalAmount = $arrRowData['total_amount'];
		
		$sth = $this->db->prepare('SELECT sum(amount)  as total_payment FROM sale_payment '.
			'WHERE statid = 9 AND sale_hdr_id = ' . $intSale);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$fltTotalPayment = $arrRowData['total_payment'];
		
		$blnFinishTransaction = FALSE;
		if ($fltTotalAmount < ($fltTotalPayment + $fltAmount)) {
			$strInfoMessage .= 'Total amount paid was greater than total amount sold.' . "\n";
		} elseif ($fltTotalAmount == ($fltTotalPayment + $fltAmount)) {
			$blnFinishTransaction = TRUE;
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$arrDataInsert = array(
			'sale_hdr_id' => $intSale,
			'payment_type_id' => $intPaymentType,
			'card_no' => $strCardNo,
			'amount' => $fltAmount,
			'statid' => 9
		);
		$this->db->sql_insert('sale_payment',$arrDataInsert);		
		
		$arrDataUpdate = array(
			'customer' => $strCustomer
		);
		$strCondition = "id = $intSale";
		$this->db->sql_update('sale_hdr',$arrDataUpdate,$strCondition);
		
		if ($blnFinishTransaction) {
			$arrDataUpdate = array(
				'statid' => 10
			);
			$strCondition = "id = $intSale";
			$this->db->sql_update('sale_hdr',$arrDataUpdate,$strCondition);
		
			$arrDataUpdate = array(
				'statid' => 10
			);
			$strCondition = "sale_hdr_id = $intSale AND statid = 9";
			$this->db->sql_update('sale_fifo',$arrDataUpdate,$strCondition);
			
			$arrDataUpdate = array(
				'statid' => 10
			);
			$strCondition = "sale_hdr_id = $intSale AND statid = 9";
			$this->db->sql_update('sale_payment',$arrDataUpdate,$strCondition);
			
			$sth = $this->db->prepare('SELECT * FROM sale_fifo '.
				'WHERE statid = 10 AND sale_hdr_id = ' . $intSale);
			$sth->execute();
			while ($arrRowData = $sth->fetch()) {
				$intSaleFifo = $arrRowData['id'];
				
				$sthSerial = $this->db->prepare('SELECT * FROM serial '.
					'WHERE statid = 9 AND transaction_no = ' . $intSaleFifo);
				$sthSerial->execute();
				while ($arrRowDataSerial = $sthSerial->fetch()) {
					$arrDataUpdate = array(
						'statid' => 10
					);
					$strCondition = 'id = ' . $arrRowDataSerial['id'];
					$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
					
					$arrDataInsert = array(
						'serial_id' => $arrRowDataSerial['id'],
						'branch_id' => $intBranch,
						'transaction_no' => $intSaleFifo,
						'transaction_date' => $strDate,
						'remarks' => "Sell item.",
						'statid' => 10
					);
					$this->db->sql_insert('serial_history',$arrDataInsert);
				}
			}
			$arrResultData['info_message'] = 'Sell item complete!';
			$arrResultData['finish'] = 1;
		}
		
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_sell_item_cancel($arrPostData,$intBranch)
	{
		$arrResultData = array('valid' => 0,'finish' => 0);
		
		if (!$arrPostData['hidSaleId']) {
			$arrResultData['valid'] = 1;
			return $arrResultData;
		}
		
		$intSale = $arrPostData['hidSaleId'];
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$arrDataUpdate = array(
			'statid' => 11
		);
		$strCondition = 'id = ' . $intSale;
		$this->db->sql_update('sale_hdr',$arrDataUpdate,$strCondition);
		
		$arrDataUpdate = array(
			'statid' => 11
		);
		$strCondition = 'statid = 9 AND sale_hdr_id = ' . $intSale;
		$this->db->sql_update('sale_fifo',$arrDataUpdate,$strCondition);
		
		$arrDataUpdate = array(
			'statid' => 11
		);
		$strCondition = 'statid = 9 AND sale_hdr_id = ' . $intSale;
		$this->db->sql_update('sale_payment',$arrDataUpdate,$strCondition);
		
		$sth = $this->db->prepare('SELECT * FROM sale_fifo '.
			'WHERE statid = 11 AND sale_hdr_id = ' . $intSale);
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$intSaleFifo = $arrRowData['id'];
			
			$sthSerial = $this->db->prepare('SELECT * FROM serial '.
				'WHERE statid = 9 AND transaction_no = ' . $intSaleFifo);
			$sthSerial->execute();
			while ($arrRowDataSerial = $sthSerial->fetch()) {
				$arrDataUpdate = array(
					'statid' => 1
				);
				$strCondition = 'id = ' . $arrRowDataSerial['id'];
				$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
				
				$arrDataInsert = array(
					'serial_id' => $arrRowDataSerial['id'],
					'branch_id' => $intBranch,
					'transaction_no' => $intSaleFifo,
					'transaction_date' => $strDate,
					'remarks' => "Cance sell item.",
					'statid' => 11
				);
				$this->db->sql_insert('serial_history',$arrDataInsert);
			}
			
			$arrDataInsert = array(
				'delivery_id' => $arrRowData['delivery_id'],
				'item_id' => $arrRowData['item_id'],
				'receive_qty' => $arrRowData['qty_sold'],
				'receive_date' => $strToday,
				'transaction' => $arrRowData['id'],
				'cost' => $arrRowData['cost'],
				'sequence_no' => 0,
				'branch_id' => $intBranch,
				'remarks' => "From cancel sell item.",
				'statid' => 1
			);
			$this->db->sql_insert('fifo',$arrDataInsert);
			
		}
		
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_sell_item_remove_item($arrPostData,$intBranch)
	{
		$intSale = $arrPostData['hidSaleId'];
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$intItemSelected = $arrPostData['item_count'];
		
		$sth = $this->db->prepare('SELECT * FROM sale_fifo '.
			'WHERE statid = 9 AND sale_hdr_id = ' . $intSale . ' GROUP BY item_id,srp ORDER BY id');
		$sth->execute();
		$intCount = 1;
		while ($rowRowData = $sth->fetch()) {
			if ($intCount == $intItemSelected) {
				$intItem = $rowRowData['item_id'];
				$fltSrp = $rowRowData['srp'];
				
				$sthSaleFifo = $this->db->prepare('SELECT * FROM sale_fifo '.
					'WHERE statid = 9 AND sale_hdr_id = ' .$intSale. ' and item_id = ' .$intItem. 
						' and srp = ' . $fltSrp);
				$sthSaleFifo->execute();
				while ($rowRowDataSaleFifo = $sthSaleFifo->fetch()) {
					$intSaleFifo = $rowRowDataSaleFifo['id'];
					
					$arrDataUpdate = array(
						'statid' => 12
					);
					$strCondition = 'id = ' . $intSaleFifo;
					$this->db->sql_update('sale_fifo',$arrDataUpdate,$strCondition);
					
					
					$arrDataInsert = array(
						'delivery_id' => $rowRowDataSaleFifo['delivery_id'],
						'item_id' => $rowRowDataSaleFifo['item_id'],
						'receive_qty' => $rowRowDataSaleFifo['qty_sold'],
						'receive_date' => $strToday,
						'transaction' => $rowRowDataSaleFifo['id'],
						'cost' => $rowRowDataSaleFifo['cost'],
						'sequence_no' => 0,
						'branch_id' => $intBranch,
						'remarks' => "From remove item.",
						'statid' => 1
					);
					$this->db->sql_insert('fifo',$arrDataInsert);
					
					$sthSerial = $this->db->prepare('SELECT * FROM serial '.
						'WHERE statid = 9 AND transaction_no = ' . $intSaleFifo);
					$sthSerial->execute();
					while ($arrRowDataSerial = $sthSerial->fetch()) {
						$arrDataUpdate = array(
							'statid' => 1
						);
						$strCondition = 'id = ' . $arrRowDataSerial['id'];
						$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
						
						$arrDataInsert = array(
							'serial_id' => $arrRowDataSerial['id'],
							'branch_id' => $intBranch,
							'transaction_no' => $intSaleFifo,
							'transaction_date' => $strDate,
							'remarks' => "From remove item.",
							'statid' => 12
						);
						$this->db->sql_insert('serial_history',$arrDataInsert);
					}
				
				}
				
				break;
			}
			$intCount++;
		}
		
	}
	
	public function xhr_sell_item_remove_payment($arrPostData)
	{
		$intSale = $arrPostData['hidSaleId'];
		$intPaymentSelected = $arrPostData['payment_count'];
		
		$sth = $this->db->prepare('SELECT * FROM sale_payment '.
			'WHERE statid = 9 AND sale_hdr_id = ' . $intSale . ' ORDER BY id');
		$sth->execute();
		$intCount = 1;
		while ($rowRowData = $sth->fetch()) {
			if ($intCount == $intPaymentSelected) {
				$intSalePayment = $rowRowData['id'];
				
				$arrDataUpdate = array(
					'statid' => 12
				);
				$strCondition = 'id = ' . $intSalePayment;
				$this->db->sql_update('sale_payment',$arrDataUpdate,$strCondition);
				
				break;
			}
			$intCount++;
		}
		
		return;
	}
	
	public function xhr_sell_item_promo_discount($arrPostData)
	{
		$intPromo = $arrPostData['selPromoList'];
		$arrResultData = array();
		
		$sth = $this->db->prepare('SELECT * FROM promotion WHERE id = ' . $intPromo);
		$sth->execute();
		$rowRowData = $sth->fetch();
		$arrResultData['promo_discount'] = $rowRowData['discount'];	
		$arrResultData['net_sales'] = $arrPostData['txtSellingPrice'] - $rowRowData['discount'];	
		return $arrResultData;
	}
	
	public function xhr_sell_item_replacement_credit_list($arrPostData,$intBranch)
	{
		$arrResultData = array();
		
		$sth = $this->db->prepare('SELECT * FROM replacement_credit WHERE statid = 1 and branch_id = ' . $intBranch);
		$sth->execute();
		$arrReplacementCreditList = array();
		while ($arrRowData = $sth->fetch()) {
			$arrSaleReturnDetails = $this->db->db_sale_return_hdr($arrRowData['sale_return_hdr_id']);
			$arrSaleDetails = $this->db->db_sale_hdr($arrSaleReturnDetails['sale_hdr_id']);
			$arrRowData['value'] = 'From sales no ' . $arrSaleDetails['invoice'] . ', amount '.$arrRowData['amount'] . '.';
			array_push($arrReplacementCreditList,$arrRowData);
		}
		$arrResultData['replacement_credit_list'] = $arrReplacementCreditList;
		
		return $arrResultData;
	}
	
	public function xhr_sell_item_replacement_credit_amount($arrPostData)
	{
		$arrResultData = array();
		$intReplacementCredit = $arrPostData['selReferenceList'];
		$arrResultData = $this->db->db_replacement_credit($intReplacementCredit);
		
		return $arrResultData;
	}
		
	public function item_list() 
	{
		$sth = $this->db->prepare('SELECT id,name FROM item WHERE statid = 1 AND active = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function payment_type_list() 
	{
		$sth = $this->db->prepare('SELECT id,name FROM payment_type WHERE statid = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function sell_item($intBranch) 
	{
		$sth = $this->db->prepare('SELECT * FROM sale_hdr WHERE statid = 9 AND branch_id = ' . $intBranch);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function sell_item_list($intSale) 
	{
		$sth = $this->db->prepare('SELECT item_id,srp,sum(qty_sold) AS qty_sold FROM sale_fifo WHERE statid = 9 AND sale_hdr_id = '. $intSale . 
			' GROUP BY item_id,srp ORDER BY id');
		$sth->execute();
		$arrRowDataAll = array();
		while ($arrRowData = $sth->fetch()) {
			$arrRowData['item'] = $this->db->db_item($arrRowData['item_id'],'name');
			$arrRowDataAll[] = $arrRowData;
		}
		return $arrRowDataAll;
	}
	
	public function sell_item_promo_list($intSale) 
	{
		$sth = $this->db->prepare('SELECT item_id,srp,sum(qty_sold) AS qty_sold,discount_amount FROM sale_fifo '.
			'WHERE statid = 9 AND sale_hdr_id = '. $intSale . ' and promotion_id != 0 ' . 
			'GROUP BY item_id,srp,promotion_id ORDER BY id');
		$sth->execute();
		$arrRowDataAll = array();
		while ($arrRowData = $sth->fetch()) {
			$arrRowData['item'] = $this->db->db_item($arrRowData['item_id'],'name');
			$arrRowDataAll[] = $arrRowData;
		}
		return $arrRowDataAll;
	}
	
	public function sell_item_payment_list($intSale) 
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