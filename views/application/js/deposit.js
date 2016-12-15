$(function() {
	
	$('#frmDeposit').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var strAction = '';
		var strInfoMessage = '';
		
		if (document.activeElement.getAttribute('value') == 'Add Deposits') {
			strAction = 'xhrDepositSaveAsNew';
		} else if (document.activeElement.getAttribute('value') == 'Update') {
			strAction = 'xhrDepositUpdate';
		} else if (document.activeElement.getAttribute('value') == 'Cancel') {
			strAction = 'xhrDepositCancel';
		
		} else if (document.activeElement.getAttribute('value') == 'Confirm') {
			strAction = 'xhrDepositConfirm';
		
		} else if (document.activeElement.getAttribute('value') == 'Delete') {
			strAction = 'xhrDepositDelete';
		}
		
		var strUrl= $('#hidUrl').val() + 'a_deposit/' + strAction;
		var strData = $("#frmDeposit").serialize();
		
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					window.location.href = $('#hidUrl').val() + 'a_deposit';
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
	
	$('#btnUpdate').click(function() {
		var blnConfirm = confirm('Are you sure you want to update ' + strInfoMessage);
		return blnConfirm;
	});
	
	$('#btnCancel').click(function() {
		var blnConfirm = confirm('Are you sure you want to cancel ' + strInfoMessage);
		return blnConfirm;
	});
	
	$('#btnConfirm').click(function() {
		var blnConfirm = confirm('Are you sure you want to confirm ' + strInfoMessage);
		return blnConfirm;
	});
	
	$('#btnDelete').click(function() {
		var blnConfirm = confirm('Are you sure you want to Delete ' + strInfoMessage);
		return blnConfirm;
	});
	
});


function getDataGridDetails(intId) {
	$(document).ajaxStart(function(){
		$(".divLoader").css("display", "block");
	});
	
	var strUrl= $('#hidUrl').val() + 'a_deposit/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidDepositId]").val(data['id']);
			$("input[name=txtAccountNo]").val(data['account_no']);
			$("input[name=txtDepositDate]").val(data['deposit_date']);
			$("input[name=txtAccountName]").val(data['account_name']);
			$("input[name=txtDepositAmount]").val(data['amount']);
			$("input[name=txtDepositedBy]").val(data['deposited_by']);
			
			$("input[name=subForm]").prop('disable',true);
		} else {
			alert('Error encounter, pls inform admin.')			
		}
			
	}, 'json');
	
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
}

