<h1>Sale</h1>

<form id = "frmSale" method="post" action="<?php echo URL;?>a_sale">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<div class = "divContainerForm">
		<div class="divInsertForm">
			<label>Imei</label><input type="text" id = "txtImei" name = "txtImei" size = "30" class = "inputNumber"/><br />
			<label>Item</label>
				<select id="selItem" name = "selItem">
					<option value=""></option>
					<?php
						foreach($this->item_list as $intId => $arrList) {
							echo '<option value="'.$arrList['id'].'" >'.$arrList['name'].'</option>';
						}
					?>
				</select><br />
			<label>Qty Available</label><input type="text" id = "txtQtyAvailable" size = "5px"  class = "inputNumber" readonly /><br />
			<label>Qty To Sell</label><input type="text" id = "txtQty" size = "5px"  class = "inputNumber"/><br />
			<label>SRP</label><input type="text" id="txtSrp" class = "inputNumber" /><br />
			<label>&nbsp;</label><input type="button" id = "btnAddItem" value = "Add Item"/><br />
		</div>
		<div class="divListForm">
			<label>Customer</label><input type="text" id ="txtCustomer"  name ="txtCustomer" size = "50"/><br />
			<label>Invoice</label><input type="text" id ="txtInvoice"  name ="txtInvoice" class = "inputNumber" /><br />
			<label>Date</label><input type="text" id ="txtDate"  name ="txtDate" value = "<?php echo date("Y-m-d")?>" /><br />
			<label>Payment Type</label>
				<select id="selPaymentType"  name="selPayType">
					<option value=""></option>
					<?php
						foreach($this->pay_type_list as $intId => $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
				</select><br />
			<label>Amount</label><input type="text" id ="txtPaymentAmount"  name ="txtPaymentAmount_" class = "inputNumber" /><br />
			<label>&nbsp;</label><input type="button" id = "btnAddPayment" value = "Add Payment"/><br />
			<input type="hidden" id ="hidPaymentReferenceNo" />
		</div>
		<div class="divClear"></div>
		<br />
		<?php if($this->customer_credit_list):?>
			<div>
				<table id = "tblCustomerCredits" name = "tblCustomerCredits" class = "tblItemDetails">
					<thead>
						<tr>
							<th width = "10%">ID</th>
							<th width = "30%">From Invoice</th>
							<th width = "20%">Amount</th>
							<th width = "40%">Amount Used</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$intCount = 0;
						foreach($this->customer_credit_list as $intId => $arrList) {
							echo '<tr>';
							echo '<td align = "center">'.$arrList['id'].'</td>';
							echo '<td align = "right">'.$arrList['amount'].'</td>';
							echo '<td align = "right">'.number_format($arrList['amount'],2).'</td>';
							echo '<td align = "center">
										<input type="text" name ="'.$arrList['id'].'" class = "inputNumberCustomerCredit" readonly />
										</td>';
							echo '</tr>';
							$intCount++;
						}
					?>
					</tbody>
				</table>
			</div>
			<div class="divClear"></div>
			<br />
		<?php endif;?>
		<div>
			<table id = "tblItemDetails" name = "tblItemDetails" class = "tblItemDetails">
				<thead>
					<tr>
						<th width = "10%">Item ID</th>
						<th width = "30%">Item Description</th>
						<th width = "20%">Serial</th>
						<th width = "10%">Quantity</th>
						<th width = "15%">SRP</th>
						<th width = "15%">Subtotal</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<table class = "tblTotalItemDetails">
				<tr>
					<td width = "80%" align = "right">Grand Total</td>
					<td id = "tdGrandTotal" width = "20%" align = "right">0.00</td>
				</tr>
			</table>
		</div>
		<div class="divClear"></div>
		<br />
		<div>
			<table id = "tblPaymentDetails" name = "tblPaymentDetails" class = "tblItemDetails">
				<thead>
					<tr>
						<th width = "15%">Reference No</th>
						<th width = "25%">Reference Transaction</th>
						<th width = "10%">Payment ID</th>
						<th width = "30%">Payment Type</th>
						<th width = "20%">Amount</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<table class = "tblTotalItemDetails">
				<tr>
					<td width = "80%" align = "right">Total Payment</td>
					<td id = "tdTotalPayment" width = "20%" align = "right">0.00</td>
				</tr>
				<tr>
					<td><input type="button" id = "btnCreate" value = "Create Sale"/></td>
					<td align = "right"><input type="button" id = "btnClear" value = "Clear"/></td>
				</tr>
			</table>
		</div>
	</div>
</form>

<hr />

