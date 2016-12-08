<?php

class Branch_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function branch_add($arrData)
	{
		$sth = $this->db->sql_insert('branch',$arrData);
	}
	
	public function branch_update($arrDataUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('branch',$arrDataUpdate,$strCondition);
	}
	
	public function xhr_get_data_grid_details($arrPostData)
	{
		$arrItemDetails = array();
		$intId = $arrPostData['data_id'];
		
		$sth = $this->db->prepare('SELECT * FROM branch WHERE id = ' . $intId);
		$sth->execute();
		$arrRowData = $sth->fetch();
		return $arrRowData;
	}
	
	public function xhr_branch_save_as_new($arrPostData)
	{
		$arrRowData = array('valid'=>0);
		$strInfoMessage = '';
		
		if (!$arrPostData['txtName']) {
			$strInfoMessage = 'Name is required' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrRowData['info_message'] = $strInfoMessage;
			return $arrRowData;
		}
		
		$arrRowData['valid'] = 1;
		$arrRowData['info_message'] = 'Saving complete!';
		
		$arrData = array(
			'name' => $arrPostData['txtName'],
			'building_street' => $arrPostData['txtBuildingStreet'],
			'barangay' => $arrPostData['txtBarangay'],
			'city_district' => $arrPostData['txtCityDistrict'],
			'province' => $arrPostData['txtProvince'],
			'landline_no' => $arrPostData['txtLandlineNo'],
			'mobile_no' => $arrPostData['txtMobileNo'],
			'date_active' => $arrPostData['txtDateActive'],
			'active' => $arrPostData['selActive'],
			'statid' => 1
		);
		
		$sth = $this->db->sql_insert('branch',$arrData);
		
		return $arrRowData;
	}
	
	public function xhr_branch_update($arrPostData)
	{
		$arrRowData = array('valid'=>0);
		$strInfoMessage = '';
		
		if (!$arrPostData['txtName']) {
			$strInfoMessage = 'Name is required' . "\n";
		}
		
		if (!$arrPostData['hidBranchId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrRowData['info_message'] = $strInfoMessage;
			return $arrRowData;
		}
		
		$arrRowData['valid'] = 1;
		$arrRowData['info_message'] = 'Update complete!';
		
		$arrData = array(
			'name' => $arrPostData['txtName'],
			'building_street' => $arrPostData['txtBuildingStreet'],
			'barangay' => $arrPostData['txtBarangay'],
			'city_district' => $arrPostData['txtCityDistrict'],
			'province' => $arrPostData['txtProvince'],
			'landline_no' => $arrPostData['txtLandlineNo'],
			'mobile_no' => $arrPostData['txtMobileNo'],
			'date_active' => $arrPostData['txtDateActive'],
			'active' => $arrPostData['selActive']
		);
		
		$strCondition = 'id = ' . $arrPostData['hidBranchId'];
		$sth = $this->db->sql_update('branch',$arrData,$strCondition);
		
		return $arrRowData;
	}
	
	public function xhr_branch_delete($arrPostData)
	{
		$arrRowData = array('valid'=>0);
		$strInfoMessage = '';
		
		if (!$arrPostData['txtName']) {
			$strInfoMessage = 'Name is required' . "\n";
		}
		
		if (!$arrPostData['hidBranchId']) {
			$strInfoMessage .= 'Double click the row to select an item' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrRowData['info_message'] = $strInfoMessage;
			return $arrRowData;
		}
		
		$arrRowData['valid'] = 1;
		$arrRowData['info_message'] = 'Delete complete!';
		
		$arrData = array(
			'statid' => 2
		);
		
		$strCondition = 'id = ' . $arrPostData['hidBranchId'];
		$sth = $this->db->sql_update('branch',$arrData,$strCondition);
		
		return $arrRowData;
	}
}