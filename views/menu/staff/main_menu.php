<?php
	switch ($this->user_page) {
		case 'a_delivery_receive':
		case 'a_sale_return':
		case 'a_delivery':
			$strUserMenuSelect = 'Inventory';
			break;
		case 'a_sell_item':
			$strUserMenuSelect = 'Sales';
			break;
		case 'r_inventory':
		case 'r_delivery':
		case 'r_sale':
			$strUserMenuSelect = 'Branch Reports';
			break;
	}
	
	$arrMainMenuList = array(
		'Inventory' => 'a_delivery_receive', 
		'Sales' => 'a_sell_item', 
		'Branch Reports' => 'r_inventory', 
		'Announcement' => '',
		'Tools' => 'h_change_password'
	);
	
	foreach($arrMainMenuList as $strUserMenu => $strLink) {
		if ($strUserMenuSelect == $strUserMenu)
			echo '<div class = "divMenuButtonSelected"><a href = "'.URL.$strLink.'" class = "aMenuButtonSelected">'.$strUserMenu.'</a></div>';
		else
			echo '<div class = "divMenuButton"><a href = "'.URL.$strLink.'" class = "aMenuButton">'.$strUserMenu.'</a></div>';
	}
	
	
	echo '<div class = "divContainerMenuWelcome">
			<p>Welcome, '.$this->user_fullname. '</p>
		</div>';
?>