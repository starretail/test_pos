$(function() {
	
	$('#frmBranch').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		
		var intBranch = $("input[name=hidBranchId]").val();
		var strAction = '';
		var strMessageInfo = '';
		
		if (document.activeElement.getAttribute('value') == 'Save as New') {
			strAction = 'xhrBranchSaveAsNew';
		} else if (document.activeElement.getAttribute('value') == 'Update') {
			strAction = 'xhrBranchUpdate';
		} else if (document.activeElement.getAttribute('value') == 'Delete') {
			strAction = 'xhrBranchDelete';
		}
		
		var strUrl= $('#hidUrl').val() + 't_branch/' + strAction;
		var strData = $("#frmBranch").serialize();
		
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					URL = document.getElementById('hidUrl').value;	
					mygrid = new dhtmlXGridObject("divGridReportContainer");
					mygrid.setImagesPath("codebase/imgs/"); 
					mygrid.setHeader("Id,Name,Building and Street,Barangay,City/District,Province,Landlibe No.,Mobile No.,Date Active,Status"); 
					mygrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,,#select_filter_strict"); 
					mygrid.setInitWidths("50,120,150,150,150,150,150,150,120,100");
					mygrid.setColAlign("center,left,left,left,left,left,left,left,center,center");
					mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
					mygrid.setSkin("light");
					mygrid.init();
					mygrid.load(URL+'views/tool/grid_data/branch.php');
					mygrid.attachEvent("onRowDblClicked",function(rowId,colInd){
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
	
});

function getDataGridDetails(intId) {
	$(document).ajaxStart(function(){
		$(".divLoader").css("display", "block");
	});
		
	var strUrl= $('#hidUrl').val() + 't_branch/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidBranchId]").val(data['id']);
			$("input[name=txtName]").val(data['name']);
			$("input[name=txtBuildingStreet]").val(data['building_street']);
			$("input[name=txtBarangay]").val(data['barangay']);
			$("input[name=txtCityDistrict]").val(data['city_district']);
			$("input[name=txtProvince]").val(data['province']);
			$("input[name=txtLandlineNo]").val(data['landline_no']);
			$("input[name=txtMobileNo]").val(data['mobile_no']);
			$("input[name=txtDateActive]").val(data['date_active']);
			$("select[name=selActive]").val(data['active']);
		} else {
			alert('Error encounter, pls inform admin.')			
		}
			
	}, 'json');
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
		
}

