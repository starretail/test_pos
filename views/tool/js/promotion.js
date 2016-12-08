$(function() {
	
	$('#frmPromotion').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		var intItem = $("input[name=hidItemId]").val();
		var strAction = '';
		var strMessageInfo = '';
		
		if (document.activeElement.getAttribute('value') == 'Save as New') {
			strAction = 'xhrPromotionSaveAsNew';
		} else if (document.activeElement.getAttribute('value') == 'Update') {
			strAction = 'xhrPromotionUpdate';
		} else if (document.activeElement.getAttribute('value') == 'Delete') {
			strAction = 'xhrPromotionDelete';
		}
		
		var strUrl= $('#hidUrl').val() + 't_promotion/' + strAction;
		var strData = $("#frmPromotion").serialize();
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					URL = document.getElementById('hidUrl').value;	
					mygrid = new dhtmlXGridObject("divGridReportContainer");
					mygrid.setImagesPath("codebase/imgs/"); 
					mygrid.setHeader("Id,Promo Name,Item Tag,Item Description,Branch,Discount,Start Date,End Date,Status"); 
					mygrid.attachHeader(",#text_filter,#select_filter_strict,#select_filter_strict,#select_filter_strict,,,,#select_filter_strict"); 
					mygrid.setInitWidths("50,200,200,200,200,120,150,150,150");
					mygrid.setColAlign("center,left,left,left,left,right,center,center,center");
					mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro');
					mygrid.setSkin("light");
					mygrid.init();
					mygrid.load(URL+'views/tool/grid_data/promotion.php');
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

	$('select[name=selItemTagList]').change(function() {
		var strUrl= $('#hidUrl').val() + 't_promotion/xhrPromotionSelectItemTag';
		var strData = $("#frmPromotion").serialize();
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				$('select[name=selItemList]')
					.find('option')
					.remove()
					.end()
					.append('<option value="">All</option>');
				$.each(arrData.result,function() {
				$('select[name=selItemList]')
						.append('<option value="'+this['id']+'">'+this['name']+'</option>');
				});
			} else {
				alert('Error encounter, pls inform admin.')			
			}
			
		}, 'json');
		
		
		/*
		;*/
	});
	
});

function getDataGridDetails(intId) {
	$(document).ajaxStart(function(){
		$(".divLoader").css("display", "block");
	});
	
	var strUrl= $('#hidUrl').val() + 't_promotion/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidPromotionId]").val(data['id']);
			$("select[name=selBranch]").val(data['branch_id']);
			$("select[name=selItemTagList]").val(data['item_tag']);
			$("select[name=selItemList]").val(data['item_id']);
			$("input[name=txtPromoName]").val(data['name']);
			$("input[name=txtDiscount]").val(data['discount']);
			$("input[name=txtStartDate]").val(data['start_date']);
			$("input[name=txtEndDate]").val(data['end_date']);
		} else {
			alert('Error encounter, pls inform admin.')			
		}
			
	}, 'json');
	
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
}

