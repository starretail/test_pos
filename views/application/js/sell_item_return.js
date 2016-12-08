$(function() {
	
	$("input[name=txtSalesNo]").keyup(function() {
		$("input[name=txtSalesNo]").css('color','red');
		$("input[name=hidSaleId]").val('');
		$("input[name=txtQty]").val('');
		$("textarea[name=txtaImei]").val('');
		$('select[name=selItemList]')
			.find('option')
			.remove()
			.end()
			.append('<option value=""></option>');
		if ($(this).val().length > 0) {
			var strUrl= $('#hidUrl').val() + 'a_sell_item_return/xhrSaleItemReturnSalesNo';
			var strData = $("#frmSellItemReturn").serialize();

			$.post(strUrl,strData, function( arrData,status ) {
				if (status == 'success') {
					if (arrData['valid'] == '1') {
						$("input[name=txtSalesNo]").css('color','black');
						$("input[name=hidSaleId]").val(arrData['sale_id']);
						
						$.each(arrData.sell_item_list,function() {
						$('select[name=selItemList]')
								.append('<option value="'+this['item_id']+'">'+this['item']+'</option>');
						});
					
					}
				} else {
					alert('Error encounter, pls inform admin.')			
				}
					
			}, 'json');	
		}
	});
	
	
	$('#frmSellItemReturn').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var strAction = '';
		var strInfoMessage = '';
		
		if (document.activeElement.getAttribute('value') == 'Refund') {
			strAction = 'xhrSellItemReturnRefund';
		} else if (document.activeElement.getAttribute('value') == 'Replacement') {
			strAction = 'xhrSellItemReturnReplacement';
		} else if (document.activeElement.getAttribute('value') == 'Add Item') {
			strAction = 'xhrSellItemReturnAddItem';
		}
		
		var strUrl= $('#hidUrl').val() + 'a_sell_item_return/' + strAction;
		var strData = $("#frmSellItemReturn").serialize();
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				if (arrData['valid'] == '1') {
					if (arrData['finish'] == '1') {
						strInfoMessage = arrData['info_message'];
						alert(strInfoMessage);
					}
					window.location.href = $('#hidUrl').val() + 'a_sell_item_return';
				} else {
					strInfoMessage = arrData['info_message'];
					alert(strInfoMessage);
				}
			} else {
				alert('Error encounter, pls inform admin.')			
			}
			
		}, 'json');
		
		$(document).ajaxComplete(function(){
			$(".divLoader").css("display", "none");
		});
		
		return false;
	});
	
	$('.tbl_sell_item_return_list tbody').on( 'dblclick', 'tr', function () {
		var arrRowData = $(this);
		var intCount = arrRowData.find("td:eq(0)").text();
		var strItem = arrRowData.find("td:eq(1)").text();
		var intQty = arrRowData.find("td:eq(3)").text();
		var blnConfirm = confirm('Do you want to remove ' +intQty+ ' quantity of ' +strItem+ ' ?');
		
		if (blnConfirm) {
			var strUrl= $('#hidUrl').val() + 'a_sell_item_return/xhrSellItemRemoveItem';
			var strData = $("#frmSellItem").serialize();
			strData = strData + '&item_count='+intCount;
			$.post(strUrl,strData, function( arrData,strStatus ) {
				if (strStatus == 'success') {
					window.location.href = $('#hidUrl').val() + 'a_sell_item_return';
				} else {
					alert('Error encounter, pls inform admin.')			
				}
				
			}, 'json');
		}
		
		return blnConfirm;
	});
	
	
	$('.tbl_sell_item_return_payment_list tbody').on( 'dblclick', 'tr', function () {
		var arrRowData = $(this);
		var intCount = arrRowData.find("td:eq(0)").text();
		var strPaymentType = arrRowData.find("td:eq(1)").text();
		var fltAmount = arrRowData.find("td:eq(3)").text();
		var blnConfirm = confirm('Do you want this ' +strPaymentType+ ' worth of ' +fltAmount+ ' ?');
		
		if (blnConfirm) {
			var strUrl= $('#hidUrl').val() + 'a_sell_item_return/xhrSellItemRemovePayment';
			var strData = $("#frmSellItem").serialize();
			strData = strData + '&payment_count='+intCount;
			$.post(strUrl,strData, function( arrData,strStatus ) {
				if (strStatus == 'success') {
					window.location.href = $('#hidUrl').val() + 'a_sell_item_return';
				} else {
					alert('Error encounter, pls inform admin.')			
				}
				
			}, 'json');
		}
		
		return blnConfirm;
	});
	
	
	$('#btnRefund').click(function() {
		var blnConfirm = confirm("Are you sure you want to refund this item?");
		return blnConfirm;
	});
	
	
	$('#btnReplacement').click(function() {
		var blnConfirm = confirm("Are you sure you want to replace this item?");
		return blnConfirm;
	});
	
	
	$('#btnAddItem').click(function() {
		var blnConfirm = confirm("Are you sure you want to add this item in return?");
		return blnConfirm;
	});
	
	$("input[name=hidSaleReturnId]").ready(function() {
		if ($("input[name=hidSaleReturnId]").val() != '') {
			$('input[name=txtSalesNo]').prop('readonly', true);
		} else {
			$('input[name=hidSaleReturnId]').prop('readonly', false);
		}
	});
	
});

