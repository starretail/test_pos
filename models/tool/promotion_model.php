<?php

class Promotion_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function promotion_add($arrData)
	{
		$sth = $this->db->sql_insert('promotion',$arrData);
	}
	
	public function promotion_update($arrDataUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('promotion',$arrDataUpdate,$strCondition);
	}
	
	public function xhr_get_data_grid_details($arrPostData)
	{
		$arrItemDetails = array();
		$intId = $arrPostData['data_id'];
		
		$sth = $this->db->prepare('SELECT * FROM promotion WHERE id = ' . $intId);
		$sth->execute();
		$arrRowData = $sth->fetch();
		return $arrRowData;
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
	
	public function item_tag_list() 
	{
		$sth = $this->db->prepare('SELECT distinct(tag) as tag FROM item WHERE statid = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function xhr_promotion_save_as_new($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['txtPromoName']){
			$strInfoMessage .= 'Promo name is required.' . "\n";
		}
		
		if (!$arrPostData['txtDiscount']){
			$strInfoMessage .= 'Discount is required.' . "\n";
		}
		
		if (!$arrPostData['txtStartDate']){
			$strInfoMessage .= 'Start date is required.' . "\n";
		}
		
		if (!$arrPostData['txtEndDate']){
			$strInfoMessage .= 'End date is required.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$arrData = array(
			'branch_id' => $arrPostData['selBranch'],
			'item_tag' => $arrPostData['selItemTagList'],
			'item_id' => $arrPostData['selItemList'],
			'name' => $arrPostData['txtPromoName'],
			'discount' => $arrPostData['txtDiscount'],
			'start_date' => $arrPostData['txtStartDate'],
			'end_date' => $arrPostData['txtEndDate'],
			'active' => 1,
			'statid' => 1
		);
		
		$sth = $this->db->sql_insert('promotion',$arrData);
		
		$arrResultData['info_message'] = 'Saving complete.';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_promotion_update($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['hidPromotionId']){
			$strInfoMessage .= 'Double click row to select an item.' . "\n";
		}
		
		if (!$arrPostData['txtPromoName']){
			$strInfoMessage .= 'Promo name is required.' . "\n";
		}
		
		if (!$arrPostData['txtDiscount']){
			$strInfoMessage .= 'Discount is required.' . "\n";
		}
		
		if (!$arrPostData['txtStartDate']){
			$strInfoMessage .= 'Start date is required.' . "\n";
		}
		
		if (!$arrPostData['txtEndDate']){
			$strInfoMessage .= 'End date is required.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$arrData = array(
			'branch_id' => $arrPostData['selBranch'],
			'item_tag' => $arrPostData['selItemTagList'],
			'item_id' => $arrPostData['selItemList'],
			'name' => $arrPostData['txtPromoName'],
			'discount' => $arrPostData['txtDiscount'],
			'start_date' => $arrPostData['txtStartDate'],
			'end_date' => $arrPostData['txtEndDate'],
			'active' => 1
		);
		
		$strCondition = 'id = ' . $_POST['hidPromotionId'];
		$sth = $this->db->sql_update('promotion',$arrData,$strCondition);
		
		$arrResultData['info_message'] = 'Update complete!';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_promotion_delete($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['hidPromotionId']){
			$strInfoMessage .= 'Double click row to select an item.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$arrData = array(
			'statid' => 2
		);
		
		$strCondition = 'id = ' . $_POST['hidPromotionId'];
		$sth = $this->db->sql_update('promotion',$arrData,$strCondition);
		
		$arrResultData['info_message'] = 'Delete complete!';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_promotion_select_item_tag($arrPostData)
	{
		$strItemTag = $arrPostData['selItemTagList'];
		$strCondition = '';
		if ($strItemTag) {
			$strCondition .= ' and tag = "'.$strItemTag.'"';
		}
		$arrResultData = array();
		$sth = $this->db->prepare('SELECT * FROM item WHERE statid = 1' . $strCondition);
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			array_push($arrResultData,$arrRowData);
		}
		
		return array('result' => $arrResultData);
	}
	
}