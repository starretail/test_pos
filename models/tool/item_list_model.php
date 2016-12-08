<?php

class Item_List_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function item_list_add($arrData)
	{
		$sth = $this->db->sql_insert('item',$arrData);
	}
	
	public function item_list_update($arrDataUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('item',$arrDataUpdate,$strCondition);
	}
	
	public function item_category_list()
	{
		$sth = $this->db->prepare('SELECT id,name FROM item_category WHERE statid = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function xhr_get_data_grid_details($arrPostData)
	{
		$arrItemDetails = array();
		$intId = $arrPostData['data_id'];
		
		$sth = $this->db->prepare('SELECT * FROM item WHERE id = ' . $intId);
		$sth->execute();
		$arrRowData = $sth->fetch();
		return $arrRowData;
	}
	
	public function xhr_item_list_save_as_new($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['selItemCategory']){
			$strInfoMessage .= 'Item category is required.' . "\n";
		}
		
		if (!$arrPostData['txtItemTag']){
			$strInfoMessage .= 'Item tag is required.' . "\n";
		}
		
		if (!$arrPostData['txtDescription']){
			$strInfoMessage .= 'Description is required.' . "\n";
		}
		
		if ($arrPostData['selSerial'] == ''){
			$strInfoMessage .= 'Serial is required.' . "\n";
		}
		
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$arrData = array(
			'category_id' => $_POST['selItemCategory'],
			'tag' => $_POST['txtItemTag'],
			'name' => $_POST['txtDescription'],
			'srp' => $_POST['txtSrp'],
			'dp' => $_POST['txtDp'],
			'serial' => $_POST['selSerial'],
			'active' => 1,
			'statid' => 1
		);
		
		$sth = $this->db->sql_insert('item',$arrData);
		
		$arrResultData['info_message'] = 'Saving complete.';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_item_list_update($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['hidItemId']){
			$strInfoMessage .= 'Double click row to select an item.' . "\n";
		}
		
		if (!$arrPostData['selItemCategory']){
			$strInfoMessage .= 'Item category is required.' . "\n";
		}
		
		if (!$arrPostData['txtItemTag']){
			$strInfoMessage .= 'Item tag is required.' . "\n";
		}
		
		if (!$arrPostData['txtDescription']){
			$strInfoMessage .= 'Description is required.' . "\n";
		}
		
		if ($arrPostData['selSerial'] == ''){
			$strInfoMessage .= 'Serial is required.' . "\n";
		}
		
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$arrData = array(
			'category_id' => $_POST['selItemCategory'],
			'tag' => $_POST['txtItemTag'],
			'name' => $_POST['txtDescription'],
			'srp' => $_POST['txtSrp'],
			'dp' => $_POST['txtDp'],
			'serial' => $_POST['selSerial'],
			'active' => 1
		);
		
		$strCondition = 'id = ' . $_POST['hidItemId'];
		$sth = $this->db->sql_update('item',$arrData,$strCondition);
		
		$arrResultData['info_message'] = 'Update complete!';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_item_list_delete($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['hidItemId']){
			$strInfoMessage .= 'Double click row to select an item.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$arrData = array(
			'statid' => 2
		);
		
		$strCondition = 'id = ' . $_POST['hidItemId'];
		$sth = $this->db->sql_update('item',$arrData,$strCondition);
		
		$arrResultData['info_message'] = 'Delete complete!';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
}