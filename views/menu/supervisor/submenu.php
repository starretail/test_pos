<?php
	switch ($this->user_page) {
		case 'a_delivery_receive':
		case 'a_sale_return':
		case 'a_delivery':
		case 'a_deposit':
			$arrSubMenuList = array(
				'Deliveries' => 'a_delivery_receive', 
				'Return' => 'a_sale_return',
				'Transfer' => 'a_delivery',
				'Deposit' => 'a_deposit'
			);
			break;
		case 'a_sell_item':
			$arrSubMenuList = array();
			break;
		case 'r_inventory':
		case 'r_delivery':
		case 'r_sale':
		case 'r_payment':
			$arrSubMenuList = array(
				'Inventory' => 'r_inventory', 
				'Deliveries' => 'r_delivery',
				'Sales' => 'r_sale',
				'Deposit' => 'r_deposit',
				'Payment' => 'r_payment',
			);
			break;
			
		case 't_announcement':
			$arrSubMenuList = array(
				'Announcement' => 't_announcement'
			);
			break;
			
		case 'h_change_password':
			$arrSubMenuList = array(
				'Tools' => 'h_change_password'
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