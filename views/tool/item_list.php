<form id="frmItemList" method="post" action="<?php echo URL;?>t_item_list">
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
			<div class = "divContinerInputTitle">Item Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidItemId" />
				<label>Item Category</label>
					<select name="selItemCategory">
					<option value=""></option>
					<?php
						foreach($this->item_category_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Item Tag</label><input type="text" name="txtItemTag" /><br />
				<label>Description</label><input type="text" name="txtDescription" /><br />
				<label>Selling Price</label><input type="text" name="txtSrp" /><br />
				<label>Dealer Price</label><input type="text" name="txtDp" /><br />
				<label>Serial</label>
					<select name="selSerial">
						<option value=""></option>
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" id = "btnSaveAsNew" value = "Save as New"/>
				<input type="submit" name = "subForm" id = "btnUpdate" value = "Update"/>
				<input type="button" id = "btnClear" value = "Clear"/>
				<input type="submit" name = "subForm" id = "btnDelete" value = "Delete"/>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

