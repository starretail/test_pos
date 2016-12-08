$(function() {
	
	$('#frmAnnouncement').submit(function() {
		$(document).ajaxStart(function(){
			$(".divLoader").css("display", "block");
		});
		
		
		var intBranch = $("input[name=hidAnnouncementId]").val();
		var strAction = '';
		var strMessageInfo = '';
		
		if (document.activeElement.getAttribute('value') == 'Save as New') {
			strAction = 'xhrAnnouncementSaveAsNew';
		} else if (document.activeElement.getAttribute('value') == 'Update') {
			strAction = 'xhrAnnouncementUpdate';
		} else if (document.activeElement.getAttribute('value') == 'Delete') {
			strAction = 'xhrAnnouncementDelete';
		}
		
		var strUrl= $('#hidUrl').val() + 't_announcement/' + strAction;
		var strData = $("#frmAnnouncement").serialize();
		
		$.post(strUrl,strData, function( arrData,strStatus ) {
			if (strStatus == 'success') {
				strInfoMessage = arrData['info_message'];
				alert(strInfoMessage);
				
				if (arrData['valid'] == '1') {
					URL = document.getElementById('hidUrl').value;	
					mygrid = new dhtmlXGridObject("divGridReportContainer");
					mygrid.setImagesPath("codebase/imgs/"); 
					mygrid.setHeader("Id,Subject,Message,Date,Time,Create By"); 
					mygrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,"); 
					mygrid.setInitWidths("50,120,150,150,150,150,150,150,120,100");
					mygrid.setColAlign("center,left,left,left,left,left,left,left,center,center");
					mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
					mygrid.setSkin("light");
					mygrid.init();
					mygrid.load(URL+'views/tool/grid_data/announcement.php');
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
		
	var strUrl= $('#hidUrl').val() + 't_announcement/xhrGetDataGridDetails';
	var strData = 'data_id='+intId;
	$.post(strUrl,strData, function( data,status ) {
		if (status == 'success') {
			$("input[name=hidAnnouncementId]").val(data['id']);
			$("input[name=txtSubject]").val(data['subject']);
			$("textarea[name=txtaMessage]").val(data['message']);
			
			
		} else {
			alert('Error encounter, pls inform admin.')			
		}
			
	}, 'json');
	$(document).ajaxComplete(function(){
		$(".divLoader").css("display", "none");
	});
		
}

