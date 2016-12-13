<form id="frmSellItem" method="post" action="<?php echo URL;?>a_sell_item">
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
			<table border = '1' width = '100%' class = "tbl_sell_item_list">
				<thead>
					<tr><th colspan = '5'><h4>SALES</h4></th></tr>
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
						$fltTotalSales = 0;
						foreach($this->sell_item_list as $arrList) {
							echo '<tr>
								<td align = "center">'.$intCount.'</td>
								<td>'.$arrList['item'].'</td>
								<td align = "right">'.number_format($arrList['srp'],2).'</td>
								<td align = "center">'.$arrList['qty_sold'].'</td>
								<td align = "right">'.number_format(($arrList['qty_sold'] * $arrList['srp']),2).'</td>
							</tr>';
							$fltTotalSales += ($arrList['qty_sold'] * $arrList['srp']);
							$intCount++;
						}
					?>
				</tbody>
			</table>
			<table border = '1' width = '100%'>
				<thead>
					<tr><th colspan = '5'><h4>DISCOUNT</h4></th></tr>
					<tr>
						<th width = "10%">No</th>
						<th width = "30%">Item</th>
						<th width = "20%">Unit Discount</th>
						<th width = "15%">Qty</th>
						<th width = "25%">Amount</th>
					</tr>
				</thead>
				<tbody>
					<tbody>
					<?php
						$intCount = 1;
						$fltTotalDiscount = 0;
						foreach($this->sell_item_promo_list as $arrList) {
							echo '<tr>
								<td align = "center">'.$intCount.'</td>
								<td>'.$arrList['item'].'</td>
								<td align = "right">'.number_format($arrList['discount_amount'],2).'</td>
								<td align = "center">'.$arrList['qty_sold'].'</td>
								<td align = "right">'.number_format(($arrList['qty_sold'] * $arrList['discount_amount']),2).'</td>
							</tr>';
							$fltTotalDiscount += ($arrList['qty_sold'] * $arrList['discount_amount']);
							$intCount++;
						}
						
						$fltTotalSales = $fltTotalSales - $fltTotalDiscount;
					?>
				</tbody>
				</tbody>
			</table>
			<br />
			<table border = '1' width = '100%'>
				<tbody>
					<tr>
						<td width = "50%"></td>
						<td width = "25%">Total Sales</td>
						<td width = "25%" align = "right"><?php echo number_format($fltTotalSales,2); ?></td>
					</tr>
					<tr>
						<td></td>
						<td>Sales Net of VAT</td>
						<td align = "right"><?php echo number_format(($fltTotalSales*100/112),2); ?></td>
					</tr>
					<tr>
						<td></td>
						<td>Add: VAT</td>
						<td align = "right"><?php echo number_format(($fltTotalSales*12/112),2); ?></td>
					</tr>
					<tr>
						<td></td>
						<td>Amount Due</td>
						<td style = "font-weight:bold" align = "right"><?php echo number_format($fltTotalSales,2); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class = "divContainerInput">
			<div class = "divContinerInputTitle">Sell Item Information</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidSaleId" value = "<?php echo $this->sell_item['id']; ?>" />
				<label>Date</label><input type="text" name="txtDate" class = 'datepicker' value = "<?php echo $this->sell_item['create_date']; ?>"/><br />
				<label>Sales No</label><input type="text" name="txtSalesNo" id = "txtSalesNo"/><br />
				<label>Serial No</label><input type="text" name="txtSerialNo" id = "txtSerialNo" /><br />
				<label>Item Code</label><input type="text" name="txtItemCode" /><br />
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
				<label>Selling Price</label><input type="text" name="txtSellingPrice"  readonly/ ><br />
				<label>Discount Type</label>
					<select name="selPromoList">
					<option value=""></option>
					</select><br />
				<label>Discount Amount</label><input type="text" name="txtDiscountAmount"  value = "0.00" readonly /><br />
				<label>Net Sales</label><input type="text" name="txtNetSales" readonly/ ><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" value = "Add Item"/>
				<input type="submit" name = "subForm" value = "Proceed to Payment"/>
				<input type="submit" name = "subForm" id = "btnCancel" value = "Cancel" />
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

