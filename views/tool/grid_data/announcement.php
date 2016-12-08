<?php
require_once '../../../config/paths.php';
require_once '../../../config/database.php';
require_once '../../../libs/Database.php';

class Grid_Data {
	function __construct() {
		$this->db = new Database();
	}
	
	public function index() {
		$strGrid = '<?xml version="1.0" encoding="UTF-8"?>';
		$strGrid .= '<rows>';
		
		$sth = $this->db->prepare('SELECT * FROM announcement WHERE statid = 1 ORDER BY id DESC');
		$sth->execute();
		while ($arrRowData = $sth->fetch()) {
			$arrUserProfileDetails = $this->db->db_user_profile($arrRowData['create_by']);
			$strCreateBy = $arrUserProfileDetails['first_name'] . ' ' . $arrUserProfileDetails['surname'];
			$strGrid .= '<row>
				<cell>'.$arrRowData['id'].'</cell>
				<cell>'.$arrRowData['subject'].'</cell>
				<cell>'.$arrRowData['message'].'</cell>
				<cell>'.$arrRowData['create_date'].'</cell>
				<cell>'.$arrRowData['create_time'].'</cell>
				<cell>'.$strCreateBy.'</cell>
			</row>';
		}
		$strGrid .= '</rows>';
		echo $strGrid;
	}
}


$appGridData = new Grid_Data();
$appGridData->index();