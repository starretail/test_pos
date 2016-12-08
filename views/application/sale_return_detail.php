<h1>Sales Return</h1>

<form id = "frmSaleReturnDetail" method="post" action="<?php echo URL;?>a_sale_return/sale_return_detail/<?php echo $this->sale_hdr['id'];?>">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<input id = "hidMessageInfo" type="hidden" name="hidMessageInfo" runat="server" value = "<?php echo Session::get('message_info');?>" />
	<div class = "divContainerForm">
		<label>Return Type</label>
		<select id="selReturnType" name = "selReturnType">
			<option value=""></option>
			<?php
				foreach($this->sale_return_type_list as $intId => $arrList) {
					echo '<option value="'.$arrList['id'].'" >'.$arrList['name'].'</option>';
				}
			?>
		</select><br />
		<label>Customer</label><input type="text" value = "<?php echo $this->sale_hdr['customer'];?>" size = "50"/><br />
		<label>Invoice</label><input type="text"  value = "<?php echo $this->sale_hdr['invoice'];?>" class = "inputNumber" /><br />
		<label>Date</label><input type="text"  value = "<?php echo date("Y-m-d");?>" /><br />
		<br />
		<div>
			<table id = "tblItemDetails" class = "tblItemDetails">
				<thead>
					<tr>
						<th width = "10%">Item ID</th>
						<th width = "25%">Item Description</th>
						<th width = "16%">Serial</th>
						<th width = "10%">Qty Available</th>
						<th width = "15%">Qty/Serial</th>
						<th width = "12%">SRP</th>
						<th width = "12%">Subtotal</th>
					</tr>
				</thead>
				<tbody>
						<?php
						$fltTotalAmount = 0;
						$intCount = 0;
						foreach($this->sale_fifo as $arrList) {
							echo '<tr>';
							echo '<td align = "center">'.$arrList['item_id'].'</td>';
							echo '<td align = "center">'.$arrList['item_description'].'</td>';
							echo '<td align = "center">'.$arrList['serial'].'</td>';
							echo '<td align = "center">'.($arrList['qty_sold'] - $arrList['return_qty']).'</td>';
							echo '<td align = "center"><input type="text" id = "txtInput_'.$intCount.'" name = "txtInput_'.$intCount.'" /></td>';
							echo '<td align = "right">'.number_format($arrList['srp'],2).'</td>';
							echo '<td align = "right">'.number_format($arrList['qty_sold'] * $arrList['srp'],2).'</td>';
							echo '</tr>';
							$fltTotalAmount += $arrList['qty_sold'] * $arrList['srp'];
							$intCount++;
						}
						?>
				</tbody>
			</table>
			<table class = "tblTotalItemDetails">
				<tr>
					<td width = "80%" align = "right">Grand Total</td>
					<td id = "tdGrandTotal" width = "20%" align = "right"><?php echo number_format($fltTotalAmount,2);?></td>
				</tr>
			</table>
		</div>
		<div class="divClear"></div>
		<div>
			<table id = "tblPaymentDetails" class = "tblItemDetails">
				<thead>
					<tr>
						<th width = "30%">Payment Type</th>
						<th width = "20%">Amount</th>
						<th width = "20%">Amount Return</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$fltTotalPayment = 0;
					$intCount = 0;
					foreach($this->sale_payment as $arrList) {
						echo '<tr>';
						echo '<td align = "center">'.$arrList['pay_type'].'</td>';
						echo '<td align = "right">'.number_format($arrList['amount'],2).'</td>';
						echo '<td align = "center"><input type="text" id = "txtInputAmount_'.$intCount.'" name = "txtInputAmount_'.$intCount.'" /></td>';
						echo '</tr>';
						$fltTotalPayment += $arrList['amount'];
						$intCount++;
					}
					?>
				</tbody>
			</table>
			<table class = "tblTotalItemDetails">
				<tr>
					<td width = "80%" align = "right">Total Payment</td>
					<td id = "tdGrandTotal" width = "20%" align = "right"><?php echo number_format($fltTotalPayment,2);?></td>
				</tr>
				<tr>
					<td><input type="submit" id="subReturnTransaction" name = "subForm" value = "Return Transaction"/></td>
					<td align = "right"><input type="submit" name = "subForm" value = "Back"/></td>
				</tr>
			</table>
		</div>
		
	</div>
</form>

<hr />

