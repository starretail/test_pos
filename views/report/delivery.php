<form id="frmDelivery" method="post" action="<?php echo URL;?>a_delivery">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<div class = 'divContainerMain'>
		<div class = 'divContainerMenu'>
			<?php require 'views/menu/'.$this->user_role.'/main_menu.php'; ?>
		</div>
		
		<div class = "divContainerReportDetails">
			<div class = "divContainerDetailsMenu">
				<?php require 'views/menu/'.$this->user_role.'/submenu.php'; ?>
			</div>
			<div id = "divFilterReportContainer">
				<table>
					<tr>
						<td>
							<label>Origin</label>
								<select name="selFromBranch">
								<option value=""></option>
								<?php
									foreach($this->branch_list as $arrList) {
										echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
									}
								?>
								</select>
						</td>
						<td>
							<label>From</label><input type="text" name="txtFromDate" class="datepicker" /><br />
						</td>
						<td>
							<label>View Type</label>
								<select name="selViewType">
								<option value=""></option>
								<option value="1">Complete Receive</option>
								<option value="2">Pending</option>
								</select>
						
						</td>
					</tr>
					<tr>
						<td>
							<label>Delivered to</label>
								<select name="selToBranch">
								<option value=""></option>
								<?php
									foreach($this->branch_list as $arrList) {
										echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
									}
								?>
								</select>
						</td>
						<td>
							<label>To</label><input type="text" name="txtToDate" class="datepicker" /><br />
						</td>
						<td align = "right">	
							<input type="button" id = "btnView" value = "View"/>
							<input type="button" id = "btnExport" value = "Export to Excel"/>
						</td>
					</tr>
				</table>
			</div>
			<div id = "divGridReportContainer"></div>
		</div>
		

	</div>
	<div class="divClear"></div>
</form>

<hr />

