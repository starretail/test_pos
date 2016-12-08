<?php

class Change_Password_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function xhr_change_password($arrPostData)
	{
		$arrResultData = array('valid'=>0);
		$strInfoMessage = '';
		if (!$arrPostData['txtOldPassword']){
			$strInfoMessage .= 'Old is required.' . "\n";
		}
		
		if (!$arrPostData['txtNewPassword']){
			$strInfoMessage .= 'New is required.' . "\n";
		}
		
		if (!$arrPostData['txtConfirmPassword']){
			$strInfoMessage .= 'Confrim is required.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$intUserProfile = $arrPostData['hidUserProfileId'];
		
		$sth = $this->db->prepare("SELECT * FROM authorize WHERE 
				user_profile_id = :user_profile_id and statid = 1");
		$sth->execute(array(
			':user_profile_id' => $intUserProfile
		));
		$arrRowData = $sth->fetch();
		
		if ($arrRowData['pword'] != md5($arrPostData['txtOldPassword']))  {
			$strInfoMessage .= 'Old password incorrect!' . "\n";
		}
		
		if ($arrPostData['txtNewPassword'] != $arrPostData['txtConfirmPassword']) {
			$strInfoMessage .= 'Password not match.' . "\n";
		}
		
		if (strlen($arrPostData['txtNewPassword']) < 8) {
			$strInfoMessage .= 'Password must atleast 8 characters' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrResultData['info_message'] = $strInfoMessage;
			return $arrResultData;
		}
		
		$arrDataUpdate = array(
			'pword' => md5($arrPostData['txtNewPassword'])
		);
		$strCondition = 'statid = 1 and user_profile_id = ' . $intUserProfile;
		$sth = $this->db->sql_update('authorize',$arrDataUpdate,$strCondition);
		
		$arrResultData['info_message'] = 'Change password complete!';
		$arrResultData['valid'] = 1;
		return $arrResultData;
	}
}