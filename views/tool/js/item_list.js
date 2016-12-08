$(function() {
	$('#frmItemList').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var strAction = '';
		var strInfoMessage = '';
		
		if (document.activeElement.getAttribute('value') == 'Save as New') {
			strAction = 'xhrItemListSaveAsNew';
		} else if (document.activeElement.getAttribute('value') == 'Update') {
			strAction = 'xhrItemListUpdate';
		} else if (document.activeElement.getAttribute('value') == 'Delete') {
			strAction = 'xhrItemListDelete';
		} else if (document.activeElement.getAttribute('value') == 'Password Reset') {
			strAction = 'xhrItemListPasswordReset';
		}
		
		var strUrl= $('#hidUrl').val() + 't_item_list/' + strAction;
		var strData = $("#frmItemList").serialize();
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					URL = document.getElementById('hidUrl').value;	
					mygrid = new dhtmlXGridObject("divGridReportContainer");
					mygrid.setImagesPath("codebase/imgs/"); 
					mygrid.setHeader("Id,Description,Item Tag,Item Category,Selling Price,Dealer Price,Serial"); 
					mygrid.attachHeader(",#text_filter,#select_filter_strict,#select_filter_strict,,,#select_filter_strict"); 
					mygrid.setInitWidths("50,200,200,200,120,120,120");
					mygrid.setColAlign("center,left,left,left,right,right,center");
					mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro');
					mygrid.setSkin("light");
					mygrid.init();
					mygrid.load(URL+'views/tool/grid_data/item_list.php');
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
	
	var strUrl= $('#hidUrl').val() + 't_item_list/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidItemId]").val(data['id']);
			$("select[name=selItemCategory]").val(data['category_id']);
			$("input[name=hidUserProfileId]").val(data['id']);
			$("input[name=txtDescription]").val(data['name']);
			$("input[name=txtItemTag]").val(data['tag']);
			$("input[name=txtSrp]").val(data['srp']);
			$("input[name=txtDp]").val(data['dp']);
			$("select[name=selSerial]").val(data['serial']);
		} else {
			alert('Error encounter, pls inform admin.');		
		}
			
	}, 'json');
	
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
}

