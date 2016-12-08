$(function() {
	$('#btnAddItem').click(function() {
		var intItem = $("#selItem").val();
		var strImei = $("#txtImei").val();
		var strItem = $("#selItem option:selected").text();
		var intQty = $("#txtQty").val();
		var fltSrp = $("#txtSrp").val();
		var fltTotalSrp = $("#tdGrandTotal").text();
		
		intQty = intQty.replace(",","");
		fltSrp = fltSrp.replace(",","");
		
		strMessageInfo = '';
		if (intItem == '') {
			strMessageInfo = strMessageInfo + 'Item is requied.' + "\n";
		}
		if (intQty == '' || $.isNumeric(intQty) == false) {
			strMessageInfo = strMessageInfo + 'Qty is requied.'+ "\n";
		}
		if (fltSrp == '' || $.isNumeric(fltSrp) == false) {
			strMessageInfo = strMessageInfo + 'Srp is requied.'+ "\n";
		}
		
		if (strMessageInfo != "") {
			alert(strMessageInfo);
			return;
		}
		
		$("#txtImei").val("");
		$("#selItem").val("");
		$("#txtQtyAvailable").val("");
		$("#txtQty").val("");
		$("#txtSrp").val("");
		
		fltTotalSrp = parseInt(fltTotalSrp) + parseInt(intQty * fltSrp);
		$('#tblItemDetails').append(
			'<tr>'+
				'<td align = "center">'+intItem+'</td>'+
				'<td>'+strItem+'</td>'+
				'<td align = "center">'+strImei+'</td>'+
				'<td align = "right">'+intQty+'</td>'+
				'<td align = "right">'+parseFloat(fltSrp).toFixed(2)+'</td>'+
				'<td align = "right">'+parseFloat(intQty * fltSrp).toFixed(2)+'</td>'+
			'</tr>'
		);		
		
		$("#tdGrandTotal").text(parseFloat(fltTotalSrp).toFixed(2));
		$("#txtTotalPayment").val(parseFloat(fltTotalSrp).toFixed(2));
		
	});
	
	$('#btnAddPayment').click(function() {
		var intPaymentType = $("#selPaymentType").val();
		var strPaymentType = $("#selPaymentType option:selected").text();
		var fltPaymentAmount = $("#txtPaymentAmount").val();
		var fltTotalPayment = $("#tdTotalPayment").text();
		
		fltPaymentAmount = fltPaymentAmount.replace(",","");
		
		strMessageInfo = '';
		if (intPaymentType == '') {
			strMessageInfo = strMessageInfo + 'Payment type is requied.' + "\n";
		}
		if (fltPaymentAmount == '' || $.isNumeric(fltPaymentAmount) == false) {
			strMessageInfo = strMessageInfo + 'Payment amount is requied.'+ "\n";
		}
		
		if (strMessageInfo != "") {
			alert(strMessageInfo);
			return;
		}
		
		var strReferenceTransaction = '-';
		var intReferenceNo = '-';
		
		if (intPaymentType == 2) {
			intReferenceNo = $("#hidPaymentReferenceNo").val();
			strReferenceTransaction = 'Customer Credit';
		}
		
		$("#selPaymentType").val("");
		$("#txtPaymentAmount").val("");
		$(".inputNumberCustomerCredit").val("");
		
		fltTotalPayment = parseInt(fltTotalPayment) + parseInt(fltPaymentAmount);
		$('#tblPaymentDetails').append(
			'<tr>'+
				'<td align = "center">'+intReferenceNo+'</td>'+
				'<td align = "center">'+strReferenceTransaction+'</td>'+
				'<td align = "center">'+intPaymentType+'</td>'+
				'<td align = "center">'+strPaymentType+'</td>'+
				'<td align = "right">'+parseFloat(fltPaymentAmount).toFixed(2)+'</td>'+
			'</tr>'
		);		
		
		$("#tdTotalPayment").text(parseFloat(fltTotalPayment).toFixed(2));
		
	});
	
	$('#btnClear').click(function() {
		$('#frmSale')[0].reset();
		$("#tblItemDetails tbody > tr").remove();
		$("#tdGrandTotal").text("0.00");
		$("#tblPaymentDetails tbody > tr").remove();
		$("#tdTotalPayment").text("0.00");
	});
	
	$('#btnCreate').click(function() {
		var strCustomer = $("#txtCustomer").val();
		var strInvoice = $("#txtInvoice").val();
		var intPayType = $("#selPayType").val();
		var fltGrandTotal = $("#tdGrandTotal").text();
		var fltTotalPayment = $("#tdTotalPayment").text();
		var strMessageInfo = '';
		
		if (strCustomer == '') {
			strMessageInfo = strMessageInfo + 'Customer is requied.' + "\n";
		}
		
		if (strInvoice == '') {
			strMessageInfo = strMessageInfo + 'Invoice is requied.'+ "\n";
		}
		
		if (($("#tblItemDetails > tbody > tr").length) == 0) {
			strMessageInfo = strMessageInfo + 'Please add item.'+ "\n";
		}
		
		if (($("#tblPaymentDetails > tbody > tr").length) == 0) {
			strMessageInfo = strMessageInfo + 'Please add payment.'+ "\n";
		}
		
		if (fltGrandTotal != fltTotalPayment) {
			strMessageInfo = strMessageInfo + 'Total amount and payment details are not equal.'+ "\n";
		}
		
		if (strMessageInfo != "") {
			alert(strMessageInfo);
			return;
		}
		
		var strUrl = $("#frmSale").attr('action');
		var strData = $("#frmSale").serialize();
		var strTableData = '';
		
		$('#tblItemDetails tbody > tr').each(function() {
			var currentRow=$(this);
			
			strTableData = strTableData + currentRow.find("td:eq(0)").text() + '|';    
			strTableData = strTableData + currentRow.find("td:eq(2)").text() + '|';    
			strTableData = strTableData + currentRow.find("td:eq(3)").text() + '|';
			strTableData = strTableData + currentRow.find("td:eq(4)").text() + '|m|';
			
		});
		
		strData = strData + '&tblData=' + strTableData;
		
		var strPaymentData = '';
		
		$('#tblPaymentDetails tbody > tr').each(function() {
			var currentRow=$(this);
			
			strPaymentData = strPaymentData + currentRow.find("td:eq(2)").text() + '|';    
			strPaymentData = strPaymentData + currentRow.find("td:eq(4)").text() + '|';    
			strPaymentData = strPaymentData + currentRow.find("td:eq(0)").text() + '|m|';
			
		});
		
		strData = strData + '&tblPaymentData=' + strPaymentData;
		
		$.post(strUrl,strData, function( data,status ) {
			if (status == 'success') {
				alert('Sale transaction complete.');
				
				$('#frmSale')[0].reset();
				$("#tblItemDetails tbody > tr").remove();
				$("#tdGrandTotal").text("0.00");
				$("#tblPaymentDetails tbody > tr").remove();
				$("#tdTotalPayment").text("0.00");
		
			} else {
				alert('Error encounter, pls inform admin.')			
			}
				
		});
		
		
		
	});

	$('#txtImei').keyup(function() {
		if ($(this).val().length >= 10 ) {
			var strUrl= $('#hidUrl').val() + 'a_sale/xhrGetAvailableQtyByImei';
			var strData = $("#frmSale").serialize();
			
			$.post(strUrl,strData, function( data,status ) {
				if (status == 'success') {
					$('#selItem').val(data['item_id']);
					$('#txtSrp').val(data['srp']);
					$('#txtQtyAvailable').val(data['qty_avail']);
					$('#txtQty').val(1);
				} else {
					alert('Error encounter, pls inform admin.')			
				}
					
			}, 'json');	
		}
	});
	
	$('#selItem').change(function() {
		var strUrl= $('#hidUrl').val() + 'a_sale/xhrGetAvailableQtyByItemId';
		var strData = $("#frmSale").serialize();
		
		$.post(strUrl,strData, function( data,status ) {
			if (status == 'success') {
				
				if (data['serial'] == 1) {
					alert('Imei is required in this item.');
					$('#selItem').val(data['item_id']);
				}
				
				$('#txtSrp').val(data['srp']);
				$('#txtQtyAvailable').val(data['qty_avail']);
				$('#txtImei').val('');
			
			} else {
				alert('Error encounter, pls inform admin.')			
			}
				
		}, 'json');
	});
	
	
	$('.inputNumberCustomerCredit').keyup(function() {
		if ($(this).val().length > 0 ) {
			var fltCustpmerCreditAmount = $(this).val();
			fltCustpmerCreditAmount = fltCustpmerCreditAmount.replace(",","");
			
			if (fltCustpmerCreditAmount == '' || $.isNumeric(fltCustpmerCreditAmount) == false) {
				alert('Invalid input');
				$(this).val('');
				$('#txtPaymentAmount').val('');
			} else {
				$('#hidPaymentReferenceNo').val($(this).attr('name'));
				$('#txtPaymentAmount').val(fltCustpmerCreditAmount);
			}
		} else {
			$('#txtPaymentAmount').val('');
		}
		
	});
	
	$('#selPaymentType').change(function() {
		$('#txtPaymentAmount').val('');
		$('.inputNumberCustomerCredit').val('');
		if ($(this).val() == 2) {//Customer Credit
			$(".inputNumberCustomerCredit").prop("readonly", false);
			$("#txtPaymentAmount").prop("readonly", true);
		} else {
			$(".inputNumberCustomerCredit").prop("readonly", true);
			$("#txtPaymentAmount").prop("readonly", false);
		}
	});
	
});