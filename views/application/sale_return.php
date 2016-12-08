<h1>Sales Return</h1>

<form id="frmSaleReturn" method="post" action="<?php echo URL;?>t_sale_cancellation">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<input id = "hidMessageInfo" type="hidden" name="hidMessageInfo" runat="server" value = "<?php echo Session::get('message_info');?>" />
	<div class = "divContainerForm">
		<div class ="divInsertForm">
			<label>Stock Location</label>
				<select id = "selStockLocation">
					<option value=""></option>
					<?php
						foreach($this->stock_location_list as $intId => $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
				</select><br />
			<label>Invoice No</label><input id = "txtInvoice" type="text" /><input type="button" id = "btnSearch" value = "Search"/>
		</div>
		<div class ="divListForm">
		</div>
		<div class="divClear"></div>
		<div id = "divGridReportContainer"></div>
	</div>
</form>

<hr />

