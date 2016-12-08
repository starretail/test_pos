<form id="frmDeliveryReceive" method="post" action="<?php echo URL;?>a_delivery_receive">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<div class = 'divLoader'></div>
	<div class = 'divContainerMain'>
		<div class = 'divContainerMenu'>
			<?php require 'views/menu/'.$this->user_role.'/main_menu.php'; ?>
		</div>
		
		<div class = "divContainerDetails">
			<div class = "divContainerDetailsMenu">
				<?php require 'views/menu/'.$this->user_role.'/submenu.php'; ?>
			</div>
		
		<div id = "divGridReportContainer"></div>
		</div>
		
		<div class = "divContainerInput">
			<div class = "divContinerInputTitle">Delivery Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidDeliveryId"/>
				<label>Origin</label><input type="text" name="txtFromBranch" readonly /><br />
				<label>Delivery to</label><input type="text" name="txtToBranch" readonly /><br />
				<label>Delivery No</label><input type="text" name="txtDeliveryNo" readonly /><br />
				<label>Item</label><input type="text" name="txtItem" readonly /><br />
				<label>Quantity</label><input type="text" name="txtQty" readonly /><br />
				<label>Qty Pending</label><input type="text" name="txtQtyPending" readonly /><br />
				<label>Qty Receive</label><input type="text" name="txtQtyReceive" /><br />
				<label>Imei</label><textarea name="txtaImei"/></textarea><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" id = "btnReceive" value = "Receive"/>
				<input type="button" id = "btnClear" value = "Clear"/>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

