<form id="frmSellItemReturn" method="post" action="<?php echo URL;?>a_sell_item_return">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<div class = 'divLoader'></div>
	<div class = 'divContainerMain'>
		<div class = 'divContainerMenu'>
			<?php require 'views/menu/'.$this->user_role.'/main_menu.php'; ?>
		</div>
		
		<div class = "divContainerDetails">
			<table border = '1' width = '100%' class = "tbl_sell_item_return_list">
				<thead>
					<tr><th colspan = '5'><h4>ITEM TO RETURN</h4></th></tr>
					<tr>
						<th width = "10%">No</th>
						<th width = "30%">Description</th>
						<th width = "20%">Unit Price</th>
						<th width = "15%">Qty</th>
						<th width = "25%">Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$intCount = 1;
						$fltTotalReturn = 0;
						foreach($this->sell_item_return_list as $arrList) {
							echo '<tr>
								<td align = "center">'.$intCount.'</td>
								<td>'.$arrList['item'].'</td>
								<td align = "right">'.number_format($arrList['price'],2).'</td>
								<td align = "center">'.$arrList['qty_return'].'</td>
								<td align = "right">'.number_format(($arrList['qty_return'] * $arrList['price']),2).'</td>
							</tr>';
							$fltTotalReturn += ($arrList['qty_return'] * $arrList['price']);
							$intCount++;
						}
					?>
				</tbody>
			</table>
			<br />
			<table border = '1' width = '100%'>
				<tbody>
					<tr>
						<td width = "50%"></td>
						<td width = "25%">Total Return</td>
						<td width = "25%" align = "right"><?php echo number_format($fltTotalReturn,2); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class = "divContainerInput">
			<div class = "divContinerInputTitle">Return Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidSaleReturnId" value = "<?php echo $this->sell_item_return['id']; ?>" />
				<input type="hidden" name="hidSaleId"  value = "<?php echo $this->sell_item_return['sale_hdr_id']; ?>" />
				<label>Sales No</label><input type="text" name="txtSalesNo" value = "<?php echo $this->sell_item_return['invoice']; ?>"/><br />
				<label>Item</label>
					<select name="selItemList">
					<option value=""></option>
					<?php
						foreach($this->item_list as $arrList) {
							echo '<option value="'.$arrList['id'].'">'.$arrList['name'].'</option>';
						}
					?>
					</select><br />
				<label>Qty</label><input type="text" name="txtQty" /><br />
				<label>Serial</label><textarea name="txtaSerial"/></textarea><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" id = "btnAddItem" value = "Add Item"/>
				<input type="submit" name = "subForm" id = "btnRefund" value = "Refund"/>
				<input type="submit" name = "subForm" id = "btnReplacement" value = "Replacement"/>
				<input type="submit" name = "subForm" id = "btnCancel" value = "Cancel" />
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

