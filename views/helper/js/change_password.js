$(function() {
	
	$('#frmChangePassword').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var intChangePassword = $("input[name=hidUserProfileId]").val();
		var strAction = '';
		var strMessageInfo = '';
		
		if (document.activeElement.getAttribute('value') == 'Change Password') {
			strAction = 'xhrChangePassword';
		}
		
		var strUrl= $('#hidUrl').val() + 'h_change_password/' + strAction;
		var strData = $("#frmChangePassword").serialize();
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					$("input[name=txtOldPassword]").val('');
					$("input[name=txtNewPassword]").val('');
					$("input[name=txtConfirmPassword]").val('');
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
	
	
});
