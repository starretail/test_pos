<?php

class Sale_Return_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function stock_location_list() 
	{
		$sth = $this->db->prepare('SELECT * FROM stock_location WHERE statid = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function sale_return_type_list() 
	{
		$arrRowDataAll = $this->db->db_sale_return_type_list();
		return $arrRowDataAll;
	}
	
	public function sale_item_details($intSaleHdr)
	{
		$arrDetails = array();
		$arrRowDataAll = $this->db->db_sale_fifo($intSaleHdr);
		foreach ($arrRowDataAll as $arrRowData) {
			$intSaleFifo = $arrRowData['id'];
			$arrDetails[$intSaleFifo]['item_id'] = $arrRowData['item_id'];
			$arrDetails[$intSaleFifo]['item_description'] = $this->db->db_item($arrRowData['item_id'],'name');
			$arrDetails[$intSaleFifo]['serial'] = $this->serial_get_by_transaction_no($arrRowData['id']);
			$arrDetails[$intSaleFifo]['qty_sold'] = $arrRowData['qty_sold'];
			$arrDetails[$intSaleFifo]['srp'] = $arrRowData['srp'];
			
			//$intQtyReturn = $this->sale_return_fifo_qty($intSaleFifo);
			$intQtyReturn = 0;
			
			$arrDetails[$arrRowData['id']]['return_qty'] = $intQtyReturn;
		}
		return $arrDetails;
	}
	
	public function sale_payment_details($intSaleHdr)
	{
		$arrDetails = array();
		$arrRowDataAll = $this->db->db_sale_payment($intSaleHdr);
		foreach ($arrRowDataAll as $arrRowData) {
			$arrDetails[$arrRowData['id']]['pay_type'] = $this->db->db_payment_type($arrRowData['pay_type_id'],'name');
			$arrDetails[$arrRowData['id']]['amount'] = $arrRowData['amount'];
		}
		return $arrDetails;
	}
	
	public function serial_get_by_transaction_no($intSaleFifo)
	{
		$sth = $this->db->prepare('SELECT * FROM serial WHERE statid = 10 and transaction_no = ' .$intSaleFifo);
		$sth->execute();
		$arrRowData = $sth->fetch();
		return $arrRowData['imei'];
	}
	
	public function sale_return_fifo_qty($intSaleFifo)
	{
		$sth = $this->db->prepare('SELECT sum(qty_return)  as total_qty_return FROM sale_return_fifo '.
			'WHERE statid = 1 and sale_fifo_id = ' . $intSaleFifo);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$intStockLocation = $arrRowData['stock_location_id'];
		
		return $arrDetails;
	}
	
	public function sale_return_transaction($intSaleHdr)
	{
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strDateTime = $strDate . ' ' . $strTime;
		
		$sth = $this->db->prepare('SELECT * FROM sale_hdr WHERE id = ' . $intSaleHdr);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$intStockLocation = $arrRowData['stock_location_id'];
		
		$sth = $this->db->prepare('INSERT INTO sale_return_hdr 
			(`sale_hdr_id`, `create_date`, `create_time`, `create_by`, `return_type_id`,  '.
				'`remarks`, `statid`, `stock_location_id`) VALUES 
			(:sale_hdr_id, :create_date, :create_time, :create_by, :return_type_id, '.
				':remarks, :statid, :stock_location_id)'
		);
		$sth->execute(array(
			':sale_hdr_id' => $intSaleHdr,
			':create_date' => $strDate,
			':create_time' => $strTime,
			':create_by' => $intUser,
			':return_type_id' => $_POST['selReturnType'],
			':remarks' => '',
			':statid' => 1,
			':stock_location_id' => $intStockLocation
		));
				
		$intSaleReturnHdr = $this->db->lastInsertId();
		
		$intCount = 0;
		$fltCustomerCreditAmount = 0;
		$arrRowDataAll = $this->db->db_sale_fifo($intSaleHdr);
		foreach ($arrRowDataAll as $arrRowData) {
			if (isset($_POST['txtInput_'.$intCount]) and $_POST['txtInput_'.$intCount]) {
				$intSaleFifo = $arrRowData['id'];
				$intPurchaseReceiveDet = $arrRowData['purchase_receive_det_id'];
				$intItem = $arrRowData['item_id'];
				$strSerial = $this->serial_get_by_transaction_no($intSaleFifo);
				$intQtyReturn = ($strSerial)?1:$_POST['txtInput_'.$intCount];
				$fltPrice = $arrRowData['srp'];
				$fltCost = $arrRowData['cost'];
				
				$fltCustomerCreditAmount += $fltPrice * $intQtyReturn;
				
				$sth = $this->db->prepare('INSERT INTO sale_return_fifo 
					(`sale_return_hdr_id`, `sale_hdr_id`, `sale_fifo_id`, `item_id`, `qty_return`,  '.
						'`price`, `cost`, `statid`) VALUES 
					(:sale_return_hdr_id, :sale_hdr_id, :sale_fifo_id, :item_id, :qty_return, '.
						':price, :cost, :statid)'
				);
				$sth->execute(array(
					':sale_return_hdr_id' => $intSaleReturnHdr,
					':sale_hdr_id' => $intSaleHdr,
					':sale_fifo_id' => $intSaleFifo,
					':item_id' => $intItem,
					':qty_return' => $intQtyReturn,
					':price' => $fltPrice,
					':cost' => $fltCost,
					':statid' => 1
				));
				$intSaleReturnFifo = $this->db->lastInsertId();
			
				$sthFifo = $this->db->prepare('INSERT INTO fifo  
					(`purchase_receive_det_id`, `item_id`, `statid`, `receive_qty`, `receive_date`,  '.
						'`remarks`, `cost`, `sequence_no`, `stock_location_id`) VALUES 
					(:purchase_receive_det_id, :item_id, :statid, :receive_qty, :receive_date, '.
						':remarks, :cost, :sequence_no, :stock_location_id)'
				);
				$sthFifo->execute(array(
					':purchase_receive_det_id' => $intPurchaseReceiveDet,
					':item_id' => $intItem,
					':statid' => 1,
					':receive_qty' => $intQtyReturn,
					':receive_date' => $strDateTime,
					':remarks' => 'From Sale Return.',
					':cost' => $fltCost,
					':sequence_no' => 0,
					':stock_location_id' => $intStockLocation
				));
				
				$sthSerial = $this->db->prepare('SELECT * FROM serial WHERE statid = 10 and transaction_no = ' . $intSaleFifo);
				$sthSerial->execute();
				$arrRowDataSerial = $sthSerial->fetch();
				if ($arrRowDataSerial) {
					$intSerial = $arrRowDataSerial['id'];
					
					$sthUpdate = $this->db->prepare('UPDATE serial 
						SET `statid` = :statid, `transaction_no` = :transaction_no, `transaction_date` = :transaction_date
						WHERE id = :id'
					);
					$sthUpdate->execute(array(
						':id' => $intSerial,
						':statid' => 1,
						':transaction_no' => 0,
						':transaction_date' => NULL
					));
					
					$sthInsert = $this->db->prepare('INSERT INTO serial_history 
						(`serial_id`, `stock_location_id`, `transaction_no`, `transaction_date`, `statid`, `remarks`) VALUES 
						(:serial_id, :stock_location_id, :transaction_no, :transaction_date, :statid, :remarks)');
					$sthInsert->execute(array(
						':serial_id' => $intSerial,
						':stock_location_id' => $intStockLocation,
						':transaction_no' => $intSaleReturnFifo,
						':transaction_date' => $strDate,
						':statid' => 12,
						':remarks' => 'From Sale Return.'
					));
				
				}
				
			}
			
			$intCount++;
		}
		
		$sthInsert = $this->db->prepare('INSERT INTO customer_credit_hdr 
			(`sale_return_hdr_id`, `amount`, `remaining_amount`, `create_date`, `statid`, `stock_location_id`) VALUES 
			(:sale_return_hdr_id, :amount, :remaining_amount, :create_date, :statid, :stock_location_id)');
		$sthInsert->execute(array(
			':sale_return_hdr_id' => $intSerial,
			':amount' => $fltCustomerCreditAmount,
			':remaining_amount' => $fltCustomerCreditAmount,
			':create_date' => $strDate,
			':statid' => 1,
			':stock_location_id' => $intStockLocation
		));
		
		$strMessageInfo = 'Sales return complete!';
		Session::set('message_info',$strMessageInfo);
		
		return;
	}
	
	
}