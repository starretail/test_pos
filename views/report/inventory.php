<form id="frmInventory" method="post" action="<?php echo URL;?>r_inventory">
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
							<label>Branch</label>
								<select name="selBranch">
								<option value=""></option>
								<?php
									foreach($this->branch_list as $arrList) {
										echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
									}
								?>
								</select>
						</td>
						<td>
							<label>Date</label><input type="text" name="txtDate" class="datepicker" /><br />
						</td>
						<td>
							<label>View Type</label>
								<select name="selViewType">
								<option value="1">Running Inventory</option>
								<option value="2">Ending Inventory</option>
								<option value="3">Serial List</option>
								</select>
						
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td>
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

