<?php

class Delivery_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function delivery_update($arrDataUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('delivery',$arrDataUpdate,$strCondition);
	}
	
	public function xhr_delivery_save_as_new($arrPostData)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0);
		$intFromBranch = $arrPostData['selFromBranch'];
		$intToBranch = $arrPostData['selToBranch'];
		$strDeliveryNo = $arrPostData['txtDeliveryNo'];
		$strDeliveryDate = $arrPostData['txtDeliveryDate'];
		$strImeiList = $arrPostData['txtaImei'];
		$intItem = $arrPostData['selItemList'];
		$intQty = $arrPostData['txtQty'];
		
		if (!$intFromBranch) {
			$strInfoMessage .= "Origin is required. \n";
		}
		
		if (!$intToBranch) {
			$strInfoMessage .= "Delivery to is required. \n";
		}
		
		if (!$strDeliveryNo) {
			$strInfoMessage .= "Delivery number is required. \n";
		}
		
		if (!$intItem) {
			$strInfoMessage .= "Item is required. \n";
		}
		
		if (!$intQty) {
			$strInfoMessage .= "Quantity is required. \n";
		}
		
		if ($intFromBranch == $intToBranch) {
			$strInfoMessage .= "Invalid selected branch. \n";
		}
		
		if ($intFromBranch != 1) {
			$arrResultData = $this->db->db_fifo_qty_available($intItem,$intFromBranch);
			if ($arrResultData['qty_available'] < $intQty) {
				$strInfoMessage .= "Quantiy to transfer is not available. \n";
			}
		} 
		
		$blnItemSerial = $this->db->db_item($intItem,'serial');
		if ($blnItemSerial) {
			if (!$strImeiList) {
				$strInfoMessage .= "Imei is required. \n";
			} else {
				$strTok = strtok($strImeiList, " \n\r\t");
				$intCount = 0;
				while ($strTok !== false) {
					$strImei = $strTok;
					$arrResultData = $this->db->db_serial_by_imei($strImei);
					
					if ($intFromBranch == 1 and $arrResultData) {//Main Office
						$strInfoMessage .= "Imei [$strImei] already exist. \n";
					} elseif ($intFromBranch != 1) {
						if (!$arrResultData) {
							$strInfoMessage .= "Imei [$strImei] does not exist. \n";
						} elseif ($arrResultData['branch_id'] != $intFromBranch) {
							$strInfoMessage .= "Imei [$strImei] does not exist in the origin branch. \n";
						} elseif ($arrResultData['statid'] != 1) {
							$strInfoMessage .= "Imei [$strImei] is not available. \n";
						}
					}
		
					$intCount++;
					$strTok = strtok(" \n\r\t");
				}
				if ($intCount != $intQty) {
					$strInfoMessage .= "Imei count does not match. \n";
				}
			}
		}
		
		$arrResultData['info_message'] = $strInfoMessage;
		if ($strInfoMessage) {
			return $arrResultData;
		}
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$arrData = array(
			'create_date' => $strDate,
			'create_time' => $strTime,
			'create_by' => $intUser,
			'from_branch_id' => $intFromBranch,
			'to_branch_id' => $intToBranch,
			'delivery_no' => $strDeliveryNo,
			'delivery_date' => $strDeliveryDate,
			'item_id' => $intItem,
			'qty' => $intQty,
			'qty_receive' => 0,
			'statid' => 6
		);
		$intDelivery = $this->db->sql_insert('delivery',$arrData);
			
		
		if ($intFromBranch == 1) {
			$arrData = array(
				'delivery_id' => $intDelivery,
				'item_id' => $intItem,
				'statid' => 6,
				'transaction_qty' => $intQty,
				'transaction_date' => $strToday,
				'transaction' => $intDelivery,
				'remarks' => "From purchase delivery.",
				'cost' => 0,
				'sequence_no' => 0,
				'branch_id' => $intFromBranch
			);
			$intFifo = $this->db->sql_insert('fifo',$arrData);
			
			if ($blnItemSerial) {
				$strTok = strtok($strImeiList, " \n\r\t");
				while ($strTok !== FALSE) {
					$strImei = $strTok;
					$strImei = trim($strImei," \n\r\t");
					$arrData = array(
						'imei' => $strImei,
						'branch_id' => $intFromBranch,
						'item_id' => $intItem,
						'transaction_no' => $intDelivery,
						'transaction_date' => $strDate,
						'statid' => 6,
						'remarks' => "From purchase delivery."
					);
					$intImei = $this->db->sql_insert('serial',$arrData);
					
					$arrData = array(
						'serial_id' => $intImei,
						'branch_id' => $intFromBranch,
						'transaction_no' => $intDelivery,
						'transaction_date' => $strDate,
						'statid' => 6,
						'remarks' => "From purchase delivery."
					);
					$this->db->sql_insert('serial_history',$arrData);
					
					$strTok = strtok(" \n\r\t");
				}
				
			}
			
		} else {
			$intTempQty = $intQty;
			do {
				$sth = $this->db->prepare('SELECT * FROM fifo '.
					'WHERE item_id = ' . $intItem . ' and statid = 1 and branch_id = ' . $intFromBranch . 
					' ORDER BY delivery_id,sequence_no');
				$sth->execute();
				$arrRowData = $sth->fetch();
				if (!$arrRowData) {
					$arrResultData['info_message'] = 'No available qty.';
					return $arrResultData;
				}
				$intFifo = $arrRowData['id'];
				$intFifoDelivery = $arrRowData['delivery_id'];
				$fltCost = $arrRowData['cost'];
				$intFifoQty = $arrRowData['receive_qty'];
				$strRemarks = $arrRowData['remarks'];
				$intSequence = $arrRowData['sequence_no'];
				
				if ($intFifoQty > $intTempQty) {
					$arrDataInsert = array(
						'delivery_id' => $intFifoDelivery,
						'item_id' => $intItem,
						'receive_qty' => ($intFifoQty - $intTempQty),
						'receive_date' => $strToday,
						'remarks' => 'After transfer item.',
						'cost' => $fltCost,
						'sequence_no' => ($intSequence + 1),
						'branch_id' => $intFromBranch,
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
					'transaction_date' => $strToday,
					'transaction' => $intDelivery,
					'remarks' => $strRemarks . 'After transfer item.',
					'statid' => 7
				);
				$strCondition = 'id = ' . $intFifo;
				$this->db->sql_update('fifo',$arrDataUpdate,$strCondition);
				
				$arrDataInsert = array(
					'delivery_id' => $intFifoDelivery,
					'item_id' => $intItem,
					'remarks' => 'Transfer item.',
					'cost' => $fltCost,
					'sequence_no' => ($intSequence + 1),
					'branch_id' => $intFromBranch,
					'transaction_qty' => $intUsedQty,
					'transaction_date' => $strToday,
					'transaction' => $intDelivery,
					'sequence_no' => ($intSequence + 1),
					'statid' => 6
				);
				$this->db->sql_insert('fifo',$arrDataInsert);
				
			} while ($intTempQty > 0);
		
			if ($blnItemSerial) {
				$strTok = strtok($strImeiList, " \n\r\t");
				while ($strTok !== FALSE) {
					$strSerial = $strTok;
					$strSerial = trim($strSerial," \n\r\t");
					
					$arrDataUpdate = array(
						'transaction_no' => $intDelivery,
						'statid' => 6
					);
					$strCondition = "imei = '$strSerial' and statid  = 1 and branch_id = $intFromBranch";
					$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
					
					$strTok = strtok(" \n\r\t");
				}
				
			}
				
			
		}
		
		$arrResultData['valid'] = 1;
		$arrResultData['info_message'] = 'Delivery saved!';
		return $arrResultData;
	}
	
	public function xhr_delivery_cancel($arrPostData)
	{
		$arrResultData = array('valid' => 0);
		$strInfoMessage = '';
		
		if (!$arrPostData['hidDeliveryId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intDelivery = $arrPostData['hidDeliveryId'];
		$sth = $this->db->prepare('SELECT * FROM delivery WHERE id = ' . $intDelivery);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$intItem = $arrRowData['item_id'];
		$intQty = $arrRowData['qty'];
		$intQtyReceive = $arrRowData['qty_receive'];
		$intFromBranch = $arrRowData['from_branch_id'];
		$blnItemSerial = $this->db->db_item($intItem,'serial');
		
		$strCancelRemarks = '';
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		
		if ($intFromBranch == 1) {
			if ($intQtyReceive) {//cancel remaining
				$strInfoMessage = 'Not yet done!';
			} else {//cancel whole delivery
				$arrDataUpdate = array(
					'cancel_date' => $strDate,
					'cancel_time' => $strTime,
					'cancel_by' => $intUser,
					'cancel_remarks' => $strCancelRemarks,
					'statid' => 28
				);
				$strCondition = 'id = ' . $intDelivery;
				$this->db->sql_update('delivery',$arrDataUpdate,$strCondition);
				
				$sth = $this->db->prepare('SELECT * FROM fifo WHERE statid = 6 and transaction = ' . $intDelivery);
				$sth->execute();
				while ($arrRowData = $sth->fetch()) {
					$intFifo = $arrRowData['id'];
					$strRemarks = $arrRowData['remarks'] . 'Cancel delivery.';
					$arrDataUpdate = array(
						'transaction_date' => $strToday,
						'remarks' => $strRemarks,
						'statid' => 28
					);
					$strCondition = 'id = ' . $intFifo;
					$this->db->sql_update('fifo',$arrDataUpdate,$strCondition);
				}
				
				if ($blnItemSerial) {
					$sth = $this->db->prepare('SELECT * FROM serial WHERE statid = 6 and transaction_no = ' . $intDelivery);
					$sth->execute();
					while ($arrRowData = $sth->fetch()) {
						$intSerial = $arrRowData['id'];
						$arrDataUpdate = array(
							'transaction_date' => $strDate,
							'remarks' => 'Cancel delivery',
							'statid' => 28
						);
						$strCondition = 'id = ' . $intSerial;
						$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
						
						$arrDataInsert = array(
							'serial_id' => $intSerial,
							'branch_id' => $intFromBranch,
							'transaction_no' => $intDelivery,
							'transaction_date' => $strDate,
							'remarks' => "Cancel delivery.",
							'statid' => 28
						);
						$this->db->sql_insert('serial_history',$arrDataInsert);
					}
				}
			}
			
			$arrResultData['valid'] = 1;
			$arrResultData['info_message'] = 'Cancel deliver complete!';
		} else {
			
		}
		return $arrResultData;
	}
	
	public function xhr_get_data_grid_details($arrPostData)
	{
		$arrItemDetails = array();
		$intId = $arrPostData['data_id'];
		$arrResultData = array();
		
		$sth = $this->db->prepare('SELECT * FROM delivery WHERE id = ' . $intId);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$intFromBranch = $arrRowData['from_branch_id'];
		$intItem = $arrRowData['item_id'];
		$arrResultData =$arrRowData;
		
		$strImeiList = '';
		$sth = $this->db->prepare("SELECT * FROM serial WHERE statid = 6 and " . 
			"branch_id = $intFromBranch and item_id = $intItem and transaction_no = $intId");
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$strImeiList .= $arrRowData['imei'] . "\n";
		}
		$arrResultData['imei_list'] = $strImeiList;
		
		return $arrResultData;
	}
	
	public function branch_list() 
	{
		$sth = $this->db->prepare('SELECT id,name FROM branch WHERE statid = 1 and active = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function item_list() 
	{
		$sth = $this->db->prepare('SELECT id,name FROM item WHERE statid = 1 and active = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
}