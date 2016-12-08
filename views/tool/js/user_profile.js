$(function() {
	
	$('#frmUserProfile').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var intUserProfile = $("input[name=hidUserProfileId]").val();
		var strAction = '';
		var strMessageInfo = '';
		
		if (document.activeElement.getAttribute('value') == 'Save as New') {
			strAction = 'xhrUserProfileSaveAsNew';
		} else if (document.activeElement.getAttribute('value') == 'Update') {
			strAction = 'xhrUserProfileUpdate';
		} else if (document.activeElement.getAttribute('value') == 'Delete') {
			strAction = 'xhrUserProfileDelete';
		} else if (document.activeElement.getAttribute('value') == 'Password Reset') {
			strAction = 'xhrUserProfilePasswordReset';
		}
		
		var strUrl= $('#hidUrl').val() + 't_user_profile/' + strAction;
		var strData = $("#frmUserProfile").serialize();
		
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					URL = document.getElementById('hidUrl').value;	
					mygrid = new dhtmlXGridObject('divGridReportContainer');
					mygrid.setImagesPath('codebase/imgs/'); 
					mygrid.setHeader('Id,Surname,First Name,Middle Name,Position,Username,'+
						'Branch,Branch Address,Landline No,Mobile No,Birthday,Address,Contact No,Date Hired,Status'); 
					mygrid.attachHeader(',#text_filter,#text_filter,#text_filter,#select_filter_strict,'+
						'#text_filter,#select_filter_strict,#text_filter,#text_filter,#text_filter,,#text_filter,#text_filter,,#select_filter_strict'); 
					mygrid.setInitWidths('50,150,150,150,150,150,150,200,150,150,150,200,150,150,150');
					mygrid.setColAlign('center,left,left,left,center,left,left,left,center,center,center,left,left,center,center');
					mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
					mygrid.setSkin('light');
					mygrid.init();
					mygrid.load(URL+'views/tool/grid_data/user_profile.php');
					mygrid.attachEvent('onRowDblClicked',function(rowId,colInd){
						getDataGridDetails(mygrid.cells(rowId,0).getValue());
					});
					
					$(".divContinerInputDetails input").val("");
					$(".divContinerInputDetails select").val("");
					
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
	
	
	$('#btnPasswordReset').click(function() {
		var blnConfirm = confirm("Are you sure you want to reset the password?");
		return blnConfirm;
	});
	
});

function getDataGridDetails(intId) {
	$(document).ajaxStart(function(){
		$(".divLoader").css("display", "block");
	});
	
	var strUrl= $('#hidUrl').val() + 't_user_profile/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
		
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidUserProfileId]").val(data['id']);
			$("input[name=txtSurname]").val(data['surname']);
			$("input[name=txtFirstName]").val(data['first_name']);
			$("input[name=txtMiddleName]").val(data['middle_name']);
			$("select[name=selEmployeePosition]").val(data['employee_position_id']);
			$("input[name=txtUserName]").val(data['user_name']);
			$("select[name=selBranch]").val(data['branch_id']);
			$("input[name=txtBirthday]").val(data['birthday']);
			$("input[name=txtAddress]").val(data['address']);
			$("input[name=txtContactNo]").val(data['contact_no']);
			$("input[name=txtDateHired]").val(data['date_hired']);
			$("select[name=selActive]").val(data['active']);
		} else {
			alert('Error encounter, pls inform admin.')			
		}
			
	}, 'json');
	
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
}

