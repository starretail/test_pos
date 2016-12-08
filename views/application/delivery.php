<form id="frmDelivery" method="post" action="<?php echo URL;?>a_delivery">
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
			<div class = "divFilterReportContainerBottom">
				<input type="button" id = "btnExport" value = "Export to Excel"/>
			</div>
		</div>
		
		<div class = "divContainerInput">
			<div class = "divContinerInputTitle">Delivery Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidDeliveryId" />
				<input type="hidden" name="hidFromBranchId" value = "<?php echo $this->from_branch;?>" />
				<input type="hidden" name="hidToBranchId" value = "<?php echo $this->to_branch;?>" />
				<?php
					if ($this->from_branch) {
						echo '<label>Origin</label><input type="text" name="txtFromBranch" value = "'.$this->from_branch_name.'"/><br />';
						echo '<input type="hidden" name="selFromBranch" value = "'.$this->from_branch.'" />';
					} else {
						echo '<label>Origin</label>
							<select name="selFromBranch">
							<option value=""></option>';
						foreach($this->branch_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
						echo '</select><br />';
					}
				?>
				
				<label>Delivery to</label>
					<select name="selToBranch">
					<option value=""></option>
					<?php
						foreach($this->branch_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Delivery Date</label><input type="text" name="txtDeliveryDate" class = "datepicker"/><br />
				<label>Item</label>
					<select name="selItemList">
					<option value=""></option>
					<?php
						foreach($this->item_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Quantity</label><input type="text" name="txtQty" /><br />
				<label>Qty Receive</label><input type="text" name="txtQtyReceive" value = "0" readonly /><br />
				<label>Imei</label><textarea name="txtaImei"/></textarea><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" id = "btnSaveAsNew" value = "Save as New"/>
				<input type="button" id = "btnClear" value = "Clear"/>
				<input type="submit" name = "subForm" id = "btnCancel" value = "Cancel"/>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

