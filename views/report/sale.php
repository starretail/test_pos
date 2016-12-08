<form id="frmSale" method="post" action="<?php echo URL;?>r_sale">
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
							<label>From Date</label><input type="text" name="txtFromDate" class="datepicker" /><br />
						</td>
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
							<label>View Type</label>
								<select name="selViewType">
								<option value="1">Gross Sales</option>
								<option value="2">Net Sales</option>
								</select>
						</td>
					</tr>
					<tr>
						<td>
							<label>To Date</label><input type="text" name="txtToDate" class="datepicker" /><br />
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

