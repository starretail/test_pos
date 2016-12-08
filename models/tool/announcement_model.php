<?php

class Announcement_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function announcement_add($strSubject  )
	{
		$sth = $this->db->sql_insert('item',$strSubject);
	}
	
	public function announcement_update($strMessageUpdate,$strCondition)
	{
		$sth = $this->db->sql_update('item',$strMessageUpdate,$strCondition);
		
	}
	
	public function xhr_get_data_grid_details($arrPostData)
	{
		$arrItemDetails = array();
		$intId = $arrPostData['data_id'];
		
		$sth = $this->db->prepare('SELECT * FROM announcement WHERE id = ' . $intId);
		$sth->execute();
		$arrRowData = $sth->fetch();
		return $arrRowData;
	}
	
	public function xhr_announcement_save_as_new($arrPostData)
	{
		$arrRowData = array();
		$arrRowData['valid'] = 0;
		$strInfoMessage = '';
		$arrRowData['info_message'] = '';
		if (!$arrPostData['txtSubject']) {
			$strInfoMessage .= 'Subject is required.' . "\n";
		}
		
		if (!$arrPostData['txtaMessage']) {
			$strInfoMessage .= 'Message is required.' . "\n";
		}
		
		if ($strInfoMessage) {
			$arrRowData['info_message'] = $strInfoMessage;
			return $arrRowData;
		}
		
		$intUser = Session::get('user_id');
		$strDate = date("Y-m-d");
		$strTime = date("H:i:s");
		$strToday = $strDate . ' ' . $strTime;
		
		$arrDataInsert = array(
			'subject' => $arrPostData['txtSubject'],
			'message' => $arrPostData['txtaMessage'],
			'create_date' => $strDate,
			'create_time' => $strTime,
			'create_by' => $intUser,
			'statid' => 1
		);
		$sth = $this->db->sql_insert('announcement',$arrDataInsert);
		
		$arrRowData['info_message'] = 'Saving Complete';
		$arrRowData['valid'] = 1;
		return $arrRowData;
	}
		public function xhr_announcement_update($arrPostData)
		{
			$arrRowData= array('valid'=>0);
			$strInfoMessage= '';
			
			if (!$arrPostData['txtSubject']){
				$strInfoMessage ='Subject is required.' . "\n";
			}
			
			if (!$arrPostData['hidAnnouncementId']){
				$strInfoMessage ='Double click row to select item.' . "\n";
			}
			
			if ($strInfoMessage){
				$arrRowData['info_message'] = $strInfoMessage;
				return $arrRowData;
			}
			
			$arrRowData['valid'] = 1;
			$arrRowData['info_message'] = 'Update complete!';

			$arrData = array(
			'subject' => $arrPostData['txtSubject'],
			'message' => $arrPostData['txtaMessage'],
			);
			
			$strCondition =' id = '.$_POST['hidAnnouncementId'];
			$sth = $this->db->sql_update('announcement',$arrData,$strCondition); 

			return $arrRowData;
		
		}
		
		public function xhr_announcement_delete($arrPostData)
		{
			$arrRowData = array('valid' =>0);
			$strInfoMessage ='';
			
		    if (!$arrPostData['txtSubject']) {
			$strInfoMessage = 'Subject is required' . "\n";
		     }
			
			if (!$arrPostData['hidAnnouncementId']){
				$strInfoMessage .= 'Double click row to select an item.' . "\n";
			}
			
			if (!$strInfoMessage) {
				$arrRowData['info_message'] = $strInfoMessage;
				return $arrRowData;
			}
			
			$arrRowData['valid'] = 1;
			$arrRowData['info_message'] = 'Delete Complete!';
			
			$arrData = array(
				'statid' => 2
			);
			
			$strCondition = 'id = '.$arrPostData['hidAnnouncementId'];
			$sth = $this->db->sql_update('announcement',$arrData,$strCondition);
			
			return $arrRowData;
		}
	}