<form id="frmBranch" method="post" action="<?php echo URL;?>t_branch">
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
			<div class = "divContinerInputTitle">Branch Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidBranchId" />
				<label>Name</label><input type="text" name="txtName" /><br />
				<label>Building & Street</label><input type="text" name="txtBuildingStreet" /><br />
				<label>Barangay</label><input type="text" name="txtBarangay" /><br />
				<label>City / District</label><input type="text" name="txtCityDistrict" /><br />
				<label>Province</label><input type="text" name="txtProvince" /><br />
				<label>Landline No.</label><input type="text" name="txtLandlineNo" /><br />
				<label>Mobile No.</label><input type="text" name="txtMobileNo" /><br />
				<label>Date Active</label><input type="text" name="txtDateActive" class="datepicker" value = "<?php echo date('Y-m-d'); ?>"/><br />
				<label>Active</label>
					<select name="selActive">
						<option value="1">Yes</option>
						<option value="2">No</option>
					</select><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" id = 'btnSaveAsNew' value = "Save as New"/>
				<input type="submit" name = "subForm" id = 'btnUpdate' value = "Update"/>
				<input type="button" id = "btnClear" value = "Clear"/>
				<input type="submit" name = "subForm" id = 'btnDelete' value = "Delete"/>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

