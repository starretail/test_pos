$(function() {
	
	$('#frmDelivery').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var strAction = '';
		var strInfoMessage = '';
		
		if (document.activeElement.getAttribute('value') == 'Save as New') {
			strAction = 'xhrDeliverySaveAsNew';
		} else if (document.activeElement.getAttribute('value') == 'Cancel') {
			strAction = 'xhrDeliveryCancel';
		}
		
		var strUrl= $('#hidUrl').val() + 'a_delivery/' + strAction;
		var strData = $("#frmDelivery").serialize();
		
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					window.location.href = $('#hidUrl').val() + 'a_delivery';
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
	
	
	$('#btnClear').click(function() {
		$(".divContinerInputDetails input").val("");
		$("input[name=subForm]").prop('disable',false);
	});
	
	
	$('#btnCancel').click(function() {
		var intQty = $("text[name=txtQty]").val();
		var intQtyReceive = $("text[name=txtQtyReceive]").val();
		
		if (intQty == intQtyReceive)  {
			strInfoMessage = 'this delivery?';
		} else {
			strInfoMessage = 'remaining qty for this delivery?';
		}
		
		var blnConfirm = confirm('Are you sure you want to cancel ' + strInfoMessage);
		return blnConfirm;
	});
	
});


function getDataGridDetails(intId) {
	$(document).ajaxStart(function(){
		$(".divLoader").css("display", "block");
	});
	
	var strUrl= $('#hidUrl').val() + 'a_delivery/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidDeliveryId]").val(data['id']);
			$("select[name=selFromBranch]").val(data['from_branch_id']);
			$("select[name=selToBranch]").val(data['to_branch_id']);
			$("input[name=txtDeliveryDate]").val(data['delivery_date']);
			$("select[name=selItemList]").val(data['item_id']);
			$("input[name=txtQty]").val(data['qty']);
			$("input[name=txtQtyReceive]").val(data['qty_receive']);
			$("textarea[name=txtaImei]").val(data['imei_list']);
			$("input[name=subForm]").prop('disable',true);
		} else {
			alert('Error encounter, pls inform admin.')			
		}
			
	}, 'json');
	
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
}

