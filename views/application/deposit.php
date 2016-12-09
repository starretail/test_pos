<form id="frmDeposit" method="post" action="<?php echo URL;?>a_deposit">
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
			<div id = "divGridReportContainer">
			</div>
			<div class = "divFilterReportContainerBottom">
				<input type="button" id = "btnExport" value = "Export to Excel"/>
			</div>
		</div>
		
		<div class = "divContainerInput">
			<div class = "divContinerInputTitle">Bank Deposit Details
        </div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidDepositId" />
				<label>Date of Deposit</label><input type="text" name="txtDepositDate" class="datepicker"/><br />
				<label>Account No</label><input type="text" name="txtAccountNo" /><br />
				<label>Account Name</label><input type="text" name="txtAccountName" /><br />
				<label>Deposit Amount</label><input type="text" name="txtDepositAmount" /><br />
				<label>Deposited By</label><input type="text" name="txtDepositedBy" /><br />			
			</div>
			<div class = "divContinerInputButton">
				<?php
					switch ($this->user_role) {
						case 'owner':
						case 'admin':
							echo '
								<input type="submit" name = "subForm" id = "btnConfirm" value = "Confirm"/>
								<input type="submit" name = "subForm" id = "btnDelete" value = "Delete"/>';
							break;
						case 'staff':
						case 'supervisor':
							echo '
								<input type="submit" name = "subForm" id = "btnSaveAsNew" value = "Add Deposits"/>
								<input type="button" id = "btnClear" value = "Clear"/>
								<input type="submit" name = "subForm" id = "btnUpdate" value = "Update"/>
								
							break;
							
					}
				?>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

