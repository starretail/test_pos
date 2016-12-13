<?php
	switch ($this->user_page) {
		case 't_branch':
		case 't_user_profile':
			$arrSubMenuList = array(
				'Branch' => 't_branch', 
				'User Profile' => 't_user_profile'
			);
			break;
		case 't_item_list':
		case 't_promotion':
		case 'a_delivery':
			$arrSubMenuList = array(
				'Item List' => 't_item_list', 
				'Promotions' => 't_promotion',
				'Deliveries' => 'a_delivery'
			);
			break;
		case 'r_inventory':
		case 'r_delivery':
		case 'r_sale':
		case 'r_deposit':
		case 'r_payment':
			$arrSubMenuList = array(
				'Inventory' => 'r_inventory', 
				'Deliveries' => 'r_delivery',
				'Sales' => 'r_sale',
				'Deposit' => 'a_deposit',
				'Card' => 'r_card',
				'Payment' => 'r_payment',
			);
			break;
		case 'a_deposit':
			$arrSubMenuList = array(
				'Deposit' => 'a_deposit',
			);
			break;
		case 't_announcement':
			$arrSubMenuList = array(
				'Announcement' => 't_announcement'
			);
			break;
			
		case 'h_change_password':
			$arrSubMenuList = array(
				'Change Passwird' => 'h_change_password'
			);
			break;
	}
	
	foreach($arrSubMenuList as $strUserSubMenu => $strLink) {
		if ($this->user_page == $strLink)
			echo '<div class = "divSubMenuButtonSelected"><a href = "'.URL.$strLink.'" class = "aSubMenuButtonSelected">'.$strUserSubMenu.'</a></div>';
		else
			echo '<div class = "divSubMenuButton"><a href = "'.URL.$strLink.'" class = "aSubMenuButton">'.$strUserSubMenu.'</a></div>';
	}
?>