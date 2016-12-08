<form id="frmUserProfile" method="post" action="<?php echo URL;?>t_user_profile">
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
			<div class = "divContinerInputTitle">Employee Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidUserProfileId" />
				<label>Branch</label>
					<select name="selBranch">
					<option value=""></option>
					<?php
						foreach($this->branch_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Position</label>
					<select name="selEmployeePosition">
					<option value=""></option>
					<?php
						foreach($this->employee_position_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Username</label><input type="text" name="txtUserName" /><br />
				<label>First Name</label><input type="text" name="txtFirstName" /><br />
				<label>Surname</label><input type="text" name="txtSurname" /><br />
				<label>Middle Name</label><input type="text" name="txtMiddleName" /><br />
				<label>Birthday</label><input type="text" name="txtBirthday" class="datepicker" /><br />
				<label>Address</label><input type="text" name="txtAddress" /><br />
				<label>Contact No.</label><input type="text" name="txtContactNo" /><br />
				<label>Date Hired</label><input type="text" name="txtDateHired" class="datepicker" /><br />
				<label>Active</label>
					<select name="selActive">
						<option value="1">Yes</option>
						<option value="2">No</option>
					</select><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" id = "btnSaveAsNew" value = "Save as New"/>
				<input type="submit" name = "subForm" id = "btnUpdate" value = "Update"/>
				<input type="submit" name = "subForm" id = "btnPasswordReset" value = "Password Reset"/>
				<input type="button" id = "btnClear" value = "Clear"/>
				<input type="submit" name = "subForm" id = "btnDelete" value = "Delete"/>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

