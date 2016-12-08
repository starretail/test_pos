<?php

class Model {

	function __construct() {
		$this->db = new Database();
	}
	
	public function check_serial_duplicate($strSerialList) 
	{
		$strInfoMessage = '';
		$arrCheckSerial = array();
		$arrSerialList = array();
		
		$intCount = 0;
		$strTok = strtok($strSerialList, " \n\r\t");
		while ($strTok !== false) {
			$intCount++;
			$strSerial = $strTok;
			if (in_array($strSerial,$arrSerialList)) {
				$strInfoMessage .= "Imei [$strSerial] has duplicate input. \n";
			}
			$arrSerialList[] = $strSerial;
			$strTok = strtok(" \n\r\t");
		}
		
		$arrCheckSerial['serial_list'] = $arrSerialList;
		$arrCheckSerial['info_message'] = $strInfoMessage;
		$arrCheckSerial['qty'] = $intCount;
		return $arrCheckSerial;
	}

}