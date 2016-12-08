$(function() {
	
	$("input[name=txtSerialNo]").keyup(function() {
		$("input[name=txtSerialNo]").css('color','red');
		$("select[name=selItemList]").val('');
		$("input[name=txtSellingPrice]").val('');
		$("input[name=txtNetSales]").val('');
		$("input[name=txtQty]").val('');
		$('select[name=selPromoList]')
			.find('option')
			.remove()
			.end()
			.append('<option value=""></option>'
		);
		$("input[name=txtDiscountAmount]").val('');
		if ($(this).val().length >= 5 ) {
			var strUrl= $('#hidUrl').val() + 'a_sell_item/xhrGetAvailableQtyBySerialNo';
			var strData = $("#frmSellItem").serialize();

			$.post(strUrl,strData, function( arrData,status ) {
				if (status == 'success') {
					if (arrData['valid'] == '1') {
						$("select[name=selItemList]").val(arrData['item_id']);
						$("input[name=txtSellingPrice]").val(arrData['selling_price']);
						$("input[name=txtNetSales]").val(arrData['net_sales']);
						$("input[name=txtSerialNo]").css('color','black');
						$("input[name=txtQty]").val('1');
						
						$('select[name=selPromoList]')
							.find('option')
							.remove()
							.end()
							.append('<option value=""></option>'
						);
						$.each(arrData.promo_list,function() {
						$('select[name=selPromoList]')
								.append('<option value="'+this['id']+'">'+this['name']+'</option>');
						});
					}
				} else {
					alert('Error encounter, pls inform admin.')			
				}
					
			}, 'json');	
		}
	});
	
	
	$("select[name=selItemList]").change(function() {
		$("input[name=txtSellingPrice]").val('');
		$("input[name=txtNetSales]").val('');
		$('select[name=selPromoList]')
			.find('option')
			.remove()
			.end()
			.append('<option value=""></option>'
		);
		$("input[name=txtDiscountAmount]").val('');
		var strUrl= $('#hidUrl').val() + 'a_sell_item/xhrGetAvailableQtyByItem';
		var strData = $("#frmSellItem").serialize();
		$.post(strUrl,strData, function( arrData,status ) {
			if (status == 'success') {
				if (arrData['valid'] == '1') {
					$("input[name=txtSerialNo]").css('color','black');
					$("input[name=txtSerialNo]").val('');
					$("input[name=txtSellingPrice]").val(arrData['selling_price']);
					$("input[name=txtNetSales]").val(arrData['net_sales']);
					$("input[name=txtSerialNo]").css('color','black');
					$('select[name=selPromoList]')
						.find('option')
						.remove()
						.end()
						.append('<option value=""></option>'
					);
					$.each(arrData.promo_list,function() {
					$('select[name=selPromoList]')
							.append('<option value="'+this['id']+'">'+this['name']+'</option>');
					});
						
				} else if(arrData['serial'] == '1'){
					$("input[name=txtSerialNo]").css('color','red');
					$("input[name=txtSerialNo]").val('Serial needed for this item.');
					$("input[name=txtSellingPrice]").val('');
					$("input[name=txtNetSales]").val('');
				}
			} else {
				alert('Error encounter, pls inform admin.')			
			}
				
		}, 'json');	
	});
	
	$("select[name=selPromoList]").change(function() {
		var intPromo = $("select[name=selPromoList]").val();
		if (intPromo != '') {
			var strUrl= $('#hidUrl').val() + 'a_sell_item/xhrSellItemPromoDiscount';
			var strData = $("#frmSellItem").serialize();
			$.post(strUrl,strData, function( arrData,status ) {
				if (status == 'success') {
					$("input[name=txtDiscountAmount]").val(arrData['promo_discount']);
					$("input[name=txtNetSales]").val(arrData['net_sales']);
				} else {
					alert('Error encounter, pls inform admin.')			
				}
					
			}, 'json');	
		} else {
			var fltSrp = $("input[name=txtSellingPrice]").val();
			$("input[name=txtNetSales]").val(fltSrp);
			$("select[name=selPromoList]").val('');
		}
	});
	
	
	$('#frmSellItem').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var strAction = '';
		var strInfoMessage = '';
		
		if (document.activeElement.getAttribute('value') == 'Add Item') {
			strAction = 'xhrSellItemAddItem';
		} else if (document.activeElement.getAttribute('value') == 'Proceed to Payment') {
			strAction = 'xhrSellItemProceedToPayment';
		} else if (document.activeElement.getAttribute('value') == 'Add Payment') {
			strAction = 'xhrSellItemAddPayment';
		} else if (document.activeElement.getAttribute('value') == 'Cancel') {
			strAction = 'xhrSellItemCancel';
		}
		
		var strUrl= $('#hidUrl').val() + 'a_sell_item/' + strAction;
		var strData = $("#frmSellItem").serialize();
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				if (arrData['valid'] == '1') {
					$("input[name=hidSaleId]").val(arrData['sale_hdr_id']);
					if (arrData['finish'] == '1') {
						strInfoMessage = arrData['info_message'];
						alert(strInfoMessage);
					}
					window.location.href = $('#hidUrl').val() + 'a_sell_item';
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
	
	$('.tbl_sell_item_list tbody').on( 'dblclick', 'tr', function () {
		var arrRowData = $(this);
		var intCount = arrRowData.find("td:eq(0)").text();
		var strItem = arrRowData.find("td:eq(1)").text();
		var intQty = arrRowData.find("td:eq(3)").text();
		var blnConfirm = confirm('Do you want to remove ' +intQty+ ' quantity of ' +strItem+ ' ?');
		
		if (blnConfirm) {
			var strUrl= $('#hidUrl').val() + 'a_sell_item/xhrSellItemRemoveItem';
			var strData = $("#frmSellItem").serialize();
			strData = strData + '&item_count='+intCount;
			$.post(strUrl,strData, function( arrData,strStatus ) {
				if (strStatus == 'success') {
					window.location.href = $('#hidUrl').val() + 'a_sell_item';
				} else {
					alert('Error encounter, pls inform admin.')			
				}
				
			}, 'json');
		}
		
		return blnConfirm;
	});
	
	
	$('.tbl_sell_item_payment_list tbody').on( 'dblclick', 'tr', function () {
		var arrRowData = $(this);
		var intCount = arrRowData.find("td:eq(0)").text();
		var strPaymentType = arrRowData.find("td:eq(1)").text();
		var fltAmount = arrRowData.find("td:eq(3)").text();
		var blnConfirm = confirm('Do you want this ' +strPaymentType+ ' worth of ' +fltAmount+ ' ?');
		
		if (blnConfirm) {
			var strUrl= $('#hidUrl').val() + 'a_sell_item/xhrSellItemRemovePayment';
			var strData = $("#frmSellItem").serialize();
			strData = strData + '&payment_count='+intCount;
			$.post(strUrl,strData, function( arrData,strStatus ) {
				if (strStatus == 'success') {
					window.location.href = $('#hidUrl').val() + 'a_sell_item';
				} else {
					alert('Error encounter, pls inform admin.')			
				}
				
			}, 'json');
		}
		
		return blnConfirm;
	});
	
	$("select[name=selPaymentType]").change(function() {
		var intPromo = $("select[name=selPaymentType]").val();
		if (intPromo == 3) {//Replacement Credit
			var strUrl= $('#hidUrl').val() + 'a_sell_item/xhrSellItemReplacementCreditList';
			var strData = $("#frmSellItem").serialize();
			$.post(strUrl,strData, function( arrData,status ) {
				if (status == 'success') {
					$('select[name=selReferenceList]')
						.find('option')
						.remove()
						.end()
						.append('<option value=""></option>'
					);
					$.each(arrData.replacement_credit_list,function() {
					$('select[name=selReferenceList]')
							.append('<option value="'+this['id']+'" >'+this['value']+'</option>');
					});
				} else {
					alert('Error encounter, pls inform admin.')			
				}
			}, 'json');	
			$('input[name=txtAmountPaid]').prop('readonly', true);
		} else {
			$('select[name=selReferenceList]')
				.find('option')
				.remove()
				.end()
				.append('<option value=""></option>'
			);
			$('input[name=txtAmountPaid]').prop('readonly', false);
		}
	});
	
	$("select[name=selReferenceList]").change(function() {
		var intPromo = $("select[name=selPaymentType]").val();
		if (intPromo == 3) {//Replacement Credit
			var strUrl= $('#hidUrl').val() + 'a_sell_item/xhrSellItemReplacementCreditAmount';
			var strData = $("#frmSellItem").serialize();
			$.post(strUrl,strData, function( arrData,status ) {
				if (status == 'success') {
					$('input[name=txtAmountPaid]').val(arrData['amount']);
				} else {
					alert('Error encounter, pls inform admin.')			
				}
			}, 'json');	
			$('input[name=txtAmountPaid]').prop('readonly', true);
		} else {
			$('select[name=selReferenceList]')
				.find('option')
				.remove()
				.end()
				.append('<option value=""></option>'
			);
			$('input[name=txtAmountPaid]').prop('readonly', false);
		}
	});
	
});

