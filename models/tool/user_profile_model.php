<?php

class User_Profile_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function user_profile_add($arrData,$strUserName)
	{
		$intUserProfile = $this->db->sql_insert('user_profile',$arrData);
		switch ($arrData['employee_position_id']) {
			case 1: $strRole = 'admin';
				break;
			case 2: $strRole = 'supervisor';
				break;
			case 3: $strRole = 'staff';
				break;
		}
		$arrAuthorizeData = array(
			'user_profile_id' => $intUserProfile,
			'uname' => $strUserName,
			'pword' => 'b4af804009cb036a4ccdc33431ef9ac9',
			'role' => $strRole,
			'branch_id' => $arrData['branch_id'],
			'statid' => 1
		);
		$this->db->sql_insert('authorize',$arrAuthorizeData);
	}
	
	public function user_profile_update($arrDataUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('user_profile',$arrDataUpdate,$strCondition);
	}
	
	public function branch_list()
	{
		$sth = $this->db->prepare('SELECT id,name FROM branch WHERE statid = 1 and active = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function employee_position_list()
	{
		$sth = $this->db->prepare('SELECT id,name FROM employee_position WHERE statid = 1');
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function xhr_get_data_grid_details($arrPostData)
	{
		$arrItemDetails = array();
		$intId = $arrPostData['data_id'];
		
		$sth = $this->db->prepare('SELECT * FROM user_profile WHERE id = ' . $intId);
		$sth->execute();
		$arrRowData = $sth->fetch();
		$arrResultData = $arrRowData;
		
		$intUserProfile = $arrRowData['id'];
		$sth = $this->db->prepare('SELECT * FROM authorize WHERE user_profile_id = ' . $intUserProfile);
		$sth->execute();
		$arrRowData = $sth->fetch();
		
		$arrResultData['user_name'] = $arrRowData['uname'];
		
		return $arrResultData;
	}
	
	public function xhr_user_profile_save_as_new($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['selBranch']){
			$strInfoMessage .= 'Branch is required.' . "\n";
		}
		
		if (!$arrPostData['selEmployeePosition']){
			$strInfoMessage .= 'Position is required.' . "\n";
		}
		
		if (!$arrPostData['txtUserName']){
			$strInfoMessage .= 'Username is required.' . "\n";
		}
		
		if (!$arrPostData['txtFirstName']){
			$strInfoMessage .= 'First name is required.' . "\n";
		}
		
		if (!$arrPostData['txtSurname']){
			$strInfoMessage .= 'Surname is required.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$strUserName = $arrPostData['txtUserName'];
		$sth = $this->db->prepare('SELECT * FROM authorize WHERE statid = 1 and uname = "' .$strUserName. '"');
		$sth->execute();
		$arrRowData = $sth->fetch();
		if ($arrRowData) {
			$arrResultData['info_message'] = 'Username already exist.';
			return $arrResultData;
		}
		
		$arrData = array(
			'branch_id' => $arrPostData['selBranch'],
			'employee_position_id' => $arrPostData['selEmployeePosition'],
			'first_name' => $arrPostData['txtFirstName'],
			'surname' => $arrPostData['txtSurname'],
			'middle_name' => $arrPostData['txtMiddleName'],
			'birthday' => $arrPostData['txtBirthday'],
			'address' => $arrPostData['txtAddress'],
			'contact_no' => $arrPostData['txtContactNo'],
			'date_hired' => $arrPostData['txtDateHired'],
			'active' => 1,
			'statid' => 1
		);

		$this->user_profile_add($arrData,$strUserName);
		
		$arrResultData['info_message'] = 'Saving complete.';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_user_profile_update($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['hidUserProfileId']){
			$strInfoMessage .= 'Double click row to select an item.' . "\n";
		}
		
		if (!$arrPostData['selBranch']){
			$strInfoMessage .= 'Branch is required.' . "\n";
		}
		
		if (!$arrPostData['selEmployeePosition']){
			$strInfoMessage .= 'Position is required.' . "\n";
		}
		
		if (!$arrPostData['txtUserName']){
			$strInfoMessage .= 'Username is required.' . "\n";
		}
		
		if (!$arrPostData['txtFirstName']){
			$strInfoMessage .= 'First name is required.' . "\n";
		}
		
		if (!$arrPostData['txtSurname']){
			$strInfoMessage .= 'Surname is required.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$strUserName = $arrPostData['txtUserName'];
		$intUserProfile = $arrPostData['hidUserProfileId'];
		
		$sth = $this->db->prepare('SELECT * FROM authorize '.
			'WHERE statid = 1 and uname = "' .$strUserName. '" and user_profile_id != ' . $intUserProfile);
		$sth->execute();
		$arrRowData = $sth->fetch();
		if ($arrRowData) {
			$arrResultData['info_message'] = 'Username already exist.';
			return $arrResultData;
		}
		
		$arrDataUpdate = array(
			'branch_id' => $arrPostData['selBranch'],
			'employee_position_id' => $arrPostData['selEmployeePosition'],
			'first_name' => $arrPostData['txtFirstName'],
			'surname' => $arrPostData['txtSurname'],
			'middle_name' => $arrPostData['txtMiddleName'],
			'birthday' => $arrPostData['txtBirthday'],
			'address' => $arrPostData['txtAddress'],
			'contact_no' => $arrPostData['txtContactNo'],
			'date_hired' => $arrPostData['txtDateHired'],
			'active' => $arrPostData['selActive'],
		);

		$strCondition = 'id = ' . $intUserProfile;
		$sth = $this->db->sql_update('user_profile',$arrDataUpdate,$strCondition);
		
		$arrResultData['info_message'] = 'Update complete.';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_user_profile_delete($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['hidUserProfileId']){
			$strInfoMessage .= 'Double click row to select an item.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$strUserName = $arrPostData['txtUserName'];
		$intUserProfile = $arrPostData['hidUserProfileId'];
		
		$arrDataUpdate = array(
			'statid' => 2,
		);
		$strCondition = 'id = ' . $intUserProfile;
		$sth = $this->db->sql_update('user_profile',$arrDataUpdate,$strCondition);
		
		$arrDataUpdate = array(
			'statid' => 2,
		);
		$strCondition = 'user_profile_id = ' . $intUserProfile;
		$sth = $this->db->sql_update('authorize',$arrDataUpdate,$strCondition);
		
		$arrResultData['info_message'] = 'Delete complete.';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
	public function xhr_user_profile_password_reset($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['hidUserProfileId']){
			$strInfoMessage .= 'Double click row to select an item.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$strUserName = $arrPostData['txtUserName'];
		$intUserProfile = $arrPostData['hidUserProfileId'];
		
		$arrDataUpdate = array(
			'pword' => 'b4af804009cb036a4ccdc33431ef9ac9',
		);
		$strCondition = 'user_profile_id = ' . $intUserProfile;
		$sth = $this->db->sql_update('authorize',$arrDataUpdate,$strCondition);
		
		$arrResultData['info_message'] = 'Password reset complete.';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
	
}