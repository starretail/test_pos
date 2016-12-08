<?php

class Delivery_Receive_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function delivery_receive_update($arrDataUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('delivery_receive',$arrDataUpdate,$strCondition);
	}
	
	public function xhr_delivery_receive($arrPostData)
	{
		$strInfoMessage = '';
		$arrResultData = array('valid' => 0);
		
		if (!$arrPostData['hidDeliveryId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if (!$arrPostData['txtQtyReceive']) {
				$strInfoMessage .= "Quantity receive is required. \n";
		}
		
		$arrResultData['info_message'] = $strInfoMessage;
		if ($strInfoMessage) {
			return $arrResultData;
		}
		
		$intDelivery = $arrPostData['hidDeliveryId'];
		$arrDelivery = $this->db->db_delivery($intDelivery);
		
		$intFromBranch = $arrDelivery['from_branch_id'];
		$intToBranch = $arrDelivery['to_branch_id'];
		$strDeliveryNo = $arrDelivery['delivery_no'];
		$intItem = $arrDelivery['item_id'];
		$intQty = $arrDelivery['qty'];
		$intQtyPending = $intQty - $arrDelivery['qty_receive'];
		
		$intQtyReceive = $arrPostData['txtQtyReceive'];
		$strSerialList = $arrPostData['txtaImei'];
		
		
		if ($intQtyReceive > $intQtyPending) {
				$strInfoMessage .= "Invalid quanity to received. \n";
		}
		
		$blnItemSerial = $this->db->db_item($intItem,'serial');
		if ($blnItemSerial) {
			if (!$strSerialList) {
				$strInfoMessage .= "Imei is required. \n";
			} else {
				$strTok = strtok($strSerialList, " \n\r\t");
				$intCount = 0;
				while ($strTok !== false) {
					$strImei = $strTok;
					$arrResultData = $this->db->db_serial_by_imei($strImei);
					if (
						$arrResultData['branch_id'] != $intFromBranch or 
						$arrResultData['item_id'] != $intItem or 
						$arrResultData['transaction_no'] != $intDelivery or 
						$arrResultData['statid'] != 6
					) {
						$strInfoMessage .= "Imei [$strImei] invalid. \n";
					}
		
					$intCount++;
					$strTok = strtok(" \n\r\t");
				}
				
				if ($intCount != $intQtyReceive) {
					$strInfoMessage .= "Imei count does not match. \n";
				}
				
			}
		}
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$intStat = ($intQtyReceive == $intQtyPending)?8:6;
		
		$arrDataUpdate = array(
			'qty_receive' => ($intQty - $intQtyPending + $intQtyReceive),
			'statid' => $intStat
		);
		$strCondition = 'id = ' . $intDelivery;
		$this->db->sql_update('delivery',$arrDataUpdate,$strCondition);
		
		$arrDataInsert = array(
			'delivery_id' => $intDelivery,
			'qty' => $intQtyReceive,
			'create_date' => $strDate,
			'create_time' => $strTime,
			'create_by' => $intUser,
			'statid' => 1
		);
		$intDeliveryReceive = $this->db->sql_insert('delivery_receive',$arrDataInsert);
		
		$intTempQty = $intQtyReceive;
		do {
			$sth = $this->db->prepare('SELECT * FROM fifo '.
				'WHERE statid = 6 AND item_id = '.$intItem.' AND transaction = ' . $intDelivery . ' AND branch_id = ' .$intFromBranch . ' '.
				'ORDER BY delivery_id,sequence_no');
			$sth->execute();
			$arrRowData = $sth->fetch();
			
			$intFifo = $arrRowData['id'];
			$intFifoDelivery = $arrRowData['delivery_id'];
			$fltCost = $arrRowData['cost'];
			$intSequenceNo= $arrRowData['sequence_no'];
			$intFifoQty= $arrRowData['transaction_qty'];
			
			if ($intFifoQty > $intTempQty) {
				$arrDataInsert = array(
					'delivery_id' => $intFifoDelivery,
					'item_id' => $intItem,
					'transaction_qty' => ($intFifoQty - $intTempQty),
					'transaction_date' => $strToday,
					'transaction' => $intDelivery,
					'remarks' => 'After receive transfer item.',
					'cost' => $fltCost,
					'sequence_no' => ($intSequenceNo + 1),
					'branch_id' => $intFromBranch,
					'statid' => 6
				);
				$this->db->sql_insert('fifo',$arrDataInsert);
				
				$intQtyUsed = $intTempQty;
				$intTempQty = 0;
			} else {
				$intQtyUsed = $intFifoQty;
				$intTempQty = $intTempQty - $intFifoQty;
			}
			
			$arrDataUpdate = array(
				'receive_qty' => $intQtyUsed,
				'receive_date' => $strToday,
				'statid' => 7
			);
			$strInfoMessage .= "intFifo: $intFifo \n";
			$strCondition = 'id = ' . $intFifo;
			$this->db->sql_update('fifo',$arrDataUpdate,$strCondition);
			
			$arrDataInsert = array(
				'delivery_id' => $intFifoDelivery,
				'item_id' => $intItem,
				'receive_qty' => $intQtyUsed,
				'receive_date' => $strToday,
				'remarks' => 'Receive transfer item.',
				'cost' => $fltCost,
				'sequence_no' => ($intSequenceNo + 1),
				'branch_id' => $intToBranch,
				'statid' => 1
			);
			$this->db->sql_insert('fifo',$arrDataInsert);
			
			$intTempQty = 0;
		} while ($intTempQty	> 0);
		
		if ($blnItemSerial) {
			$strTok = strtok($strSerialList, " \n\r\t");
			while ($strTok !== FALSE) {
				$strSerial = $strTok;
				
				$intSerial = $this->db->db_serial_by_imei($strSerial,'id');
				$arrDataUpdate = array(
					'branch_id' => $intToBranch,
					'statid' => 1,
					'remarks' => "Available."
				);
				$strCondition = 'id = ' . $intSerial;
				$this->db->sql_update('serial',$arrDataUpdate,$strCondition);
				
				$arrDataUpdate = array(
					'serial_id' => $intSerial,
					'branch_id' => $intToBranch,
					'transaction_no' => $intDeliveryReceive,
					'transaction_date' => $strDate,
					'statid' => 8,
					'remarks' => "From transfer item."
				);
				$this->db->sql_insert('serial_history',$arrDataUpdate);
				
				$strTok = strtok(" \n\r\t");
			}
		}
		
		$arrResultData['valid'] = 1;
		$arrResultData['info_message'] = 'Receive complete!';
		
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
		$intToBranch = $arrRowData['to_branch_id'];
		$intItem = $arrRowData['item_id'];
		$intQty = $arrRowData['qty'];
		$intQtyReceive = $arrRowData['qty_receive'];
		$arrResultData = $arrRowData;
		
		$arrResultData['from_branch'] = $this->db->db_branch($intFromBranch,'name');
		$arrResultData['to_branch'] = $this->db->db_branch($intToBranch,'name');
		$arrResultData['item'] = $this->db->db_item($intItem,'name');
		$arrResultData['qty_pending'] = $intQty - $intQtyReceive;
		
		return $arrResultData;
	}
	
}