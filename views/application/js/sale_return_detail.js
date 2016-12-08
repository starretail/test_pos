$(function() {
	$('#subReturnTransaction').click(function() {
		var strTableData = '';
		var strUrl = $("#frmSaleReturn").attr('action');
		var strData = $("#frmSaleReturn").serialize();
		
		var blnReturnCount = false;
		var intCount = 0;
		var strCount = '';
		var blnReturn = false;
		var strInput = '';
		var strMessageInfo = '';
		var intReturnType = $("#selReturnType").val();
		if (intReturnType == '') {
			strMessageInfo = strMessageInfo + 'Please select input type.'  + "\n";
		}
		
		$('#tblItemDetails tbody > tr').each(function() {
			var currentRow=$(this);
			var strItemDescription = currentRow.find("td:eq(1)").text();
			var strSerial = currentRow.find("td:eq(2)").text();
			
			strCount = intCount;
			strInput = $("#txtInput_" + strCount).val();
			
			if (strInput != '') {
				blnReturnCount = true;
				var intQtyAvailable = currentRow.find("td:eq(3)").text() 
				if (intQtyAvailable <= 0) {
					strMessageInfo = strMessageInfo + 'Invalid input for item ' + strItemDescription + '.'  + "\n";
				}
				
				if (strSerial != '') {
					if (strSerial != strInput)  {
						strMessageInfo = strMessageInfo + 'Invalid serial input for item ' + strItemDescription + '.'  + "\n";
					}
				} else if (intQtyAvailable < strInput) {
					strMessageInfo = strMessageInfo + 'Invalid input qty for item ' + strItemDescription + '.'  + "\n";
				}
			}
			
			intCount = intCount + 1;
		});
		
		if (blnReturnCount == false) {
			strMessageInfo = strMessageInfo + 'Please select item to return.';
		}
		
		if (strMessageInfo != '') {
			alert(strMessageInfo);
		} else {
			blnReturn = true;
		}
		return blnReturn;
	
	});
	
	$('#frmSaleReturnDetail').ready(function() {
		var strMessageInfo = $('#hidMessageInfo').val();
		if (strMessageInfo != '')
			alert(strMessageInfo);
	});
	
	
});