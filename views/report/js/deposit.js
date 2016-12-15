$(function() {
	
	$('#btnView').click(function() {
		URL = document.getElementById('hidUrl').value;
		
		var strFromDate = $("input[name=txtFromDate]").val();
		var strToDate = $("input[name=txtToDate]").val();
		var strViewType = $("select[name=selViewType]").val();
		mygrid = new dhtmlXGridObject("divGridReportContainer");
		mygrid.setImagesPath("codebase/imgs/"); 
		mygrid.setHeader("Id,Date of Deposit,Account No,Account Name,Deposit Amount,Deposited By"); 
		mygrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter");  
		mygrid.setInitWidths("50,150,150,150,150,150");
		mygrid.setColAlign("center,center,center,center,left,left");
		mygrid.setColTypes('ro,ro,ro,ro,ro,ro');
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.load(URL+'views/report/grid_data/deposit.php?'+
				'from_date='+strFromDate+'&'+
				'to_date='+strToDate+'&'+
				'view_type='+strViewType
			);
	});
	
	
	$('#btnExport').click(function() {
		URL = document.getElementById('hidUrl').value;
		mygrid.toExcel(URL+'public/grid-excel-php/generate.php');
	});
});

