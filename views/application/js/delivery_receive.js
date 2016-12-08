$(function() {
	
	$('#frmDeliveryReceive').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		/*
		var intDeliveryReceive = $("input[name=hidDeliveryId]").val();
		var intQtyReceive = $("input[name=txtQtyReceive]").val();
		var strMessageInfo = '';
		
		if (intQtyReceive == '') {
			strMessageInfo = strMessageInfo + 'Quantity to receive is requied.' + "\n";
		}
		
		if (document.activeElement.getAttribute('value') == 'Update') {
			if (intDeliveryReceive == '') {
				strMessageInfo = strMessageInfo + 'Please select branch to update by double clicking the row.' + "\n";
			}
		}
		
		if (strMessageInfo != "") {
			alert(strMessageInfo);
			
			$(document).ajaxComplete(function(){
				$(".divLoader").css("display", "none");
			});
			
			return false;
		}*/
		
		var strAction = '';
		var strInfoMessage = '';
		
		if (document.activeElement.getAttribute('value') == 'Receive') {
			strAction = 'xhrDeliveryReceive';
		}
		
		var strUrl= $('#hidUrl').val() + 'a_delivery_receive/' + strAction;
		var strData = $("#frmDeliveryReceive").serialize();
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					URL = document.getElementById('hidUrl').value;	
					mygrid = new dhtmlXGridObject("divGridReportContainer");
					mygrid.setImagesPath("codebase/imgs/"); 
					mygrid.setHeader("Id,Origin,Delievery To,DeliveryReceive No,Item,Qty Delivered,Qty Received,Status"); 
					mygrid.attachHeader(",#select_filter_strict,#select_filter_strict,#text_filter,#select_filter_strict,,,#select_filter_strict"); 
					mygrid.setInitWidths("50,200,200,150,200,120,120,150");
					mygrid.setColAlign("center,left,left,center,left,right,right,center");
					mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro');
					mygrid.setSkin("light");
					mygrid.init();
					mygrid.load(URL+'views/application/grid_data/delivery_receive.php');
					mygrid.attachEvent("onRowDblClicked",function(rowId,colInd){
						getDataGridDetails(mygrid.cells(rowId,0).getValue());
					});
					
					$(".divContinerInputDetails input").val("");
					$(".divContinerInputDetails select").val("");
					$(".divContinerInputDetails textarea").val("");
					
					 
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
	
	
	$('#btnReceive').click(function() {
		var blnConfirm = confirm("Are you sure you want to receive this?");
		return blnConfirm;
	});
	
});


function getDataGridDetails(intId) {
	$(document).ajaxStart(function(){
		$(".divLoader").css("display", "block");
	});
	
	var strUrl= $('#hidUrl').val() + 'a_delivery_receive/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidDeliveryId]").val(data['id']);
			$("input[name=txtFromBranch]").val(data['from_branch']);
			$("input[name=txtToBranch]").val(data['to_branch']);
			$("input[name=txtDeliveryNo]").val(data['delivery_no']);
			$("input[name=txtItem]").val(data['item']);
			$("input[name=txtQty]").val(data['qty']);
			$("input[name=txtQtyPending]").val(data['qty_pending']);
			$("input[name=txtQtyReceive]").val('');
			$("textarea[name=txtaImei]").val('');
		} else {
			alert('Error encounter, pls inform admin.')			
		}
			
	}, 'json');
	
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
}

