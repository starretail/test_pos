<form id="frmPromotion" method="post" action="<?php echo URL;?>t_promotion">
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
			<div class = "divContinerInputTitle">Discount Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidPromotionId" />
				<label>Branch</label>
					<select name="selBranch">
					<option value="0">All</option>
					<?php
						foreach($this->branch_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Item Tag</label>
					<select name="selItemTagList">
					<option value="">All</option>
					<?php
						foreach($this->item_tag_list as $arrList) {
							echo '<option value="'.$arrList['tag'].'">'.$arrList['tag'].'</option>';
						}
					?>
					</select><br />
				
				<label>Item</label>
					<select name="selItemList">
					<option value="0">All</option>
					<?php
						foreach($this->item_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Promo Name</label><input type="text" name="txtPromoName" /><br />
				<label>Discount</label><input type="text" name="txtDiscount" /><br />
				<label>Start Date</label><input type="text" name="txtStartDate" class="datepicker" /><br />
				<label>End Date</label><input type="text" name="txtEndDate" class="datepicker" /><br />
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

