<?php
	switch ($this->user_page) {
		case 't_branch':
		case 't_user_profile':
			$strUserMenuSelect = 'User Management';
			break;
		case 't_item_list':
		case 't_promotion':
		case 'a_delivery':
			$strUserMenuSelect = 'Merchandise Inventory';
			break;
		case 'r_inventory':
		case 'r_delivery':
		case 'r_sale':
		case 'r_deposit':
		case 'r_payment':
			$strUserMenuSelect = 'Branch Reports';
			break;
		case 'a_deposit':
			$strUserMenuSelect = 'Branch Sales';
			break;
		case 't_announcement':
			$strUserMenuSelect = 'Announcement';
			break;
		case 'h_change_password':
			$strUserMenuSelect = 'Tools';
			break;
		
	}
	$arrMainMenuList = array(
		'User Management' => 't_branch', 
		'Merchandise Inventory' => 't_item_list', 
		'Branch Reports' => 'r_inventory', 
		'Branch Sales' => 'a_deposit', 
		'Announcement' => 't_announcement',
		'Tools' => 'h_change_password'
	);
		
	foreach($arrMainMenuList as $strUserMenu => $strLink) {
		if ($strUserMenuSelect  == $strUserMenu)
			echo '<div class = "divMenuButtonSelected"><a href = "'.URL.$strLink.'" class = "aMenuButtonSelected">'.$strUserMenu.'</a></div>';
		else
			echo '<div class = "divMenuButton"><a href = "'.URL.$strLink.'" class = "aMenuButton">'.$strUserMenu.'</a></div>';
	}
	
	echo '<div class = "divContainerMenuWelcome">
			<p>Welcome, '.$this->user_fullname. '</p>
		</div>';
?>