<?php

class Sale_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function xhr_get_available_qty_by_item_id($arrPostData)
	{
		$arrItemDetails = array();
		$intItem = $arrPostData['selItem'];
		$intStockLocation = 1;//Head Office
		
		$sth = $this->db->prepare('SELECT * FROM item WHERE id = ' . $intItem);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$arrItemDetails['serial'] = $arrRowData['serial'];
		
		if ($arrRowData['serial']) {
			$arrItemDetails['item_id'] = '';
			$arrItemDetails['qty_avail'] = '';
			$arrItemDetails['srp'] = '';
			return $arrItemDetails;
		}
		
		$arrItemDetails['srp'] = number_format($arrRowData['srp'],2);
		
		$sth = $this->db->prepare('SELECT sum(receive_qty) as qty_avail FROM fifo '.
			'WHERE statid = 1 and stock_location_id = '.$intStockLocation.' and item_id = ' . $intItem);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$arrItemDetails['qty_avail'] = number_format($arrRowData['qty_avail'],0);
		
		return $arrItemDetails;
	}
	
	public function xhr_get_available_qty_by_imei($arrPostData)
	{
		$arrItemDetails = array();
		$strImei = $arrPostData['txtImei'];
		$intStockLocation = 1;//Head Office
		
		$sth = $this->db->prepare('SELECT * FROM serial '.
			'WHERE statid = 1 and stock_location_id = '.$intStockLocation.' and imei = "' . $strImei .'"');
		$sth->execute();
		if (!$sth->rowCount()) {
			$arrItemDetails['item_id'] = '';
			$arrItemDetails['srp'] = 'n/a';
			$arrItemDetails['qty_avail'] = 'n/a';
			return $arrItemDetails;
		}
		
		$arrRowData = $sth->fetch();
		$intItem = $arrRowData['item_id'];
		$arrItemDetails['item_id'] = $intItem;
		
		$sth = $this->db->prepare('SELECT * FROM item WHERE id = ' . $intItem);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$arrItemDetails['srp'] = number_format($arrRowData['srp'],2);
		$arrItemDetails['serial'] = $arrRowData['serial'];
		
		$sth = $this->db->prepare('SELECT sum(receive_qty) as qty_avail FROM fifo '.
			'WHERE statid = 1 and stock_location_id = '.$intStockLocation.' and item_id = ' . $intItem);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$arrItemDetails['qty_avail'] = number_format($arrRowData['qty_avail'],0);
		
		return $arrItemDetails;
	}
	
	public function sale_create($arrRequestData) 
	{
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strDateTime = $strDate . ' ' . $strTime;
		
		$intStockLocation = 1; //HO default
		
		$sth = $this->db->prepare('INSERT INTO sale_hdr 
			(`invoice`, `customer`, `create_date`, `create_by`, `stock_location_id`, `statid`) VALUES 
			(:invoice, :customer, :create_date, :create_by, :stock_location_id, :statid)');
		$sth->execute(array(
			':invoice' => $arrRequestData['txtInvoice'],
			':customer' => $arrRequestData['txtCustomer'],
			':create_date' => $strDate,
			':create_by' => $intUser,
			':stock_location_id' => $intStockLocation,
			':statid' => 1
		));
		
		$intSale = $this->db->lastInsertId();
		
		$arrTableItemDetails = explode("|m|",substr($arrRequestData['tblData'],0,-3));
		foreach ($arrTableItemDetails as $strItemDetails) {
			$arrItemDetails = explode('|',$strItemDetails);
			$intItem = $arrItemDetails[0];
			$strSerial = $arrItemDetails[1];
			$intQty = $arrItemDetails[2];
			$fltSrp = $arrItemDetails[3];
			
			$intQtyTemp = $intQty;
			while ($intQtyTemp > 0) {
				$sth = $this->db->prepare('SELECT * FROM fifo '.
					'WHERE statid = 1 and stock_location_id = '.$intStockLocation.' and item_id = ' . $intItem . 
					' ORDER BY purchase_receive_det_id LIMIT 1');
				$sth->execute();
				if ($sth->rowCount() > 0) {
					$arrRowData = $sth->fetch();
					$intFifo = $arrRowData['id'];
					$intPurchaseReceiveDet = $arrRowData['purchase_receive_det_id'];
					$intQtyFifo = $arrRowData['receive_qty'];
					$fltCost = $arrRowData['cost'];
					$intSeqNo = $arrRowData['sequence_no'] + 1;
					if ($intQtyFifo > $intQtyTemp) {
						$intQtyFifoRemain = $intQtyFifo - $intQtyTemp;
						$intQtyFifoSale = $intQtyTemp;
						
						$sth = $this->db->prepare('UPDATE fifo 
							SET `transaction_qty` = :transaction_qty, `transaction_date` = :transaction_date, `statid` = :statid
							WHERE id = :id'
						);
						$sth->execute(array(
							':id' => $intFifo,
							':transaction_qty' => $intQtyTemp,
							':transaction_date' => $strDateTime,
							':statid' => 7
						));
						
						$sth = $this->db->prepare('INSERT INTO fifo  
							(`purchase_receive_det_id`, `item_id`, `statid`, `receive_qty`, `receive_date`,  '.
								'`remarks`, `cost`, `sequence_no`, `stock_location_id`) VALUES 
							(:purchase_receive_det_id, :item_id, :statid, :receive_qty, :receive_date, '.
								':remarks, :cost, :sequence_no, :stock_location_id)'
						);
						$sth->execute(array(
							':purchase_receive_det_id' => $intPurchaseReceiveDet,
							':item_id' => $intItem,
							':statid' => 1,
							':receive_qty' => $intQtyFifoRemain,
							':receive_date' => $strDateTime,
							':remarks' => 'After Sale.',
							':cost' => $fltCost,
							':sequence_no' => $intSeqNo,
							':stock_location_id' => $intStockLocation
						));
						
						$intQtyTemp = 0;
					} else {
						$intQtyFifoSale = $intQtyFifo;
						
						$sth = $this->db->prepare('UPDATE fifo 
							SET `transaction_qty` = :transaction_qty, `transaction_date` = :transaction_date, `statid` = :statid
							WHERE id = :id'
						);
						$sth->execute(array(
							':id' => $intFifo,
							':transaction_qty' => $intQtyFifo,
							':transaction_date' => $strDateTime,
							':statid' => 7
						));
						$intQtyTemp = $intQtyTemp - $intQtyFifo;
					}
					
					$fltProfit = ($fltSrp - $fltCost) * $intQty;
			
					$sth = $this->db->prepare('INSERT INTO sale_fifo 
						(`sale_hdr_id`, `qty_sold`, `srp`, `cost`, `profit`, `fifo_id`, `purchase_receive_det_id`, `item_id`, `statid`) VALUES 
						(:sale_hdr_id, :qty_sold, :srp, :cost, :profit, :fifo_id, :purchase_receive_det_id, :item_id, :statid)');
					$sth->execute(array(
						':sale_hdr_id' => $intSale,
						':qty_sold' => $intQty,
						':srp' => $fltSrp,
						':cost' => $fltCost,
						':profit' => $fltProfit,
						':fifo_id' => $intFifo,
						':purchase_receive_det_id' => $intPurchaseReceiveDet,
						':item_id' => $intItem,
						':statid' => 1
					));
					
					$intSaleFifo = $this->db->lastInsertId();
		
				} else {
					$intQtyTemp = 0;
					//make error notice on qty
				}
			}// end of while
			
			if ($strSerial != '') {
				$sth = $this->db->prepare('SELECT * FROM serial '.
					'WHERE statid = 1 and stock_location_id = '.$intStockLocation.' and '.
					'item_id = ' . $intItem . ' and imei = "' . $strSerial . '" ' . 'LIMIT 1');
				$sth->execute();
				
				if ($sth->rowCount() > 0) {
					$arrRowData = $sth->fetch();
					$intSerial = $arrRowData['id'];
					$sth = $this->db->prepare('UPDATE serial 
						SET `transaction_no` = :transaction_no, `transaction_date` = :transaction_date, `statid` = :statid
						WHERE id = :id'
					);
					$sth->execute(array(
						':id' => $intSerial,
						':transaction_no' => $intSaleFifo,
						':transaction_date' => $strDateTime,
						':statid' => 10
					));
					
					$sth = $this->db->prepare('INSERT INTO serial_history 
						(`serial_id`, `stock_location_id`, `transaction_no`, `transaction_date`, `statid`, `remarks`) VALUES 
						(:serial_id, :stock_location_id, :transaction_no, :transaction_date, :statid, :remarks)');
					$sth->execute(array(
						':serial_id' => $intSerial,
						':stock_location_id' => $intStockLocation,
						':transaction_no' => $intSaleFifo,
						':transaction_date' => $strDateTime,
						':statid' => 1,
						':remarks' => 'Sold Item.'
					));
				} else {
					// imei not available, make a notice error here
				}
				
				
			}
			
		}//enf of foreach
		
		$arrTablePaymentDetails = explode("|m|",substr($arrRequestData['tblPaymentData'],0,-3));
		foreach ($arrTablePaymentDetails as $strPaymentDetails) {
			$arrPaymentDetails = explode('|',$strPaymentDetails);
			$intPayment = $arrPaymentDetails[0];
			$fltAmount = $arrPaymentDetails[1];
			$intReferenceNo = $arrPaymentDetails[2];
			
			$sth = $this->db->prepare('INSERT INTO sale_payment 
				(`sale_hdr_id`, `pay_type_id`, `amount`, `statid`) VALUES 
				(:sale_hdr_id, :pay_type_id, :amount, :statid)');
			$sth->execute(array(
				':sale_hdr_id' => $intSale,
				':pay_type_id' => $intPayment,
				':amount' => $fltAmount,
				':statid' => 1
			));
			$intSalePayment = $this->db->lastInsertId();
		
			
			if ($intPayment == 2) {//Customer Credit
				$intCustomerCredit = $intReferenceNo;
				$sthInsert = $this->db->prepare('INSERT INTO customer_credit_det 
					(`sale_hdr_id`, `sale_payment_id`, `used_amount`, `used_date`, `statid`) VALUES 
					(:sale_hdr_id, :sale_payment_id, :used_amount, :create_date, :statid)');
				$sthInsert->execute(array(
					':sale_hdr_id' => $intSale,
					':sale_payment_id' => $intSalePayment,
					':used_amount' => $fltAmount,
					':used_date' => $strDate,
					':statid' => 1
				));
				
				$sthUpdate = $this->db->prepare('UPDATE customer_credit_hdr 
					SET `remaining_amount`= remaining_amount - :remaining_amount
					WHERE id = :id'
				);
				$sthUpdate->execute(array(
					':id' => $intCustomerCredit,
					':remaining_amount' => $fltAmount
				));
			}
			
		}
		
		
		return;
	}
	
	public function item_list() 
	{
		$sth = $this->db->prepare('SELECT * FROM item WHERE statid = 1');
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function item_list_non_serial() 
	{
		$sth = $this->db->prepare('SELECT * FROM item WHERE statid = 1 and serial = 0');
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function pay_type_list() 
	{
		$sth = $this->db->prepare('SELECT * FROM pay_type WHERE statid = 1');
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function customer_credit_list() 
	{
		$intStockLocation = Session::get('stock_location_id');
		//$intStockLocation = 1;
		$sth = $this->db->prepare('SELECT * FROM customer_credit_hdr '.
			'WHERE remaining_amount > 0 and statid = 1 and stock_location_id = '.$intStockLocation);
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	
	public function xhrGetDataGridDetails() 
	{
	 	echo json_encode($this->model->xhr_get_data_grid_details($_POST));
	}
	
}