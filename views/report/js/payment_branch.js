$(function() {
	
	$('#btnView').click(function() {
		URL = document.getElementById('hidUrl').value;
		
		var strFromDate = $("input[name=txtFromDate]").val();
		var strToDate = $("input[name=txtToDate]").val();
		var intBranch = $("input[name=selBranch]").val();
		var strViewType = $("select[name=selViewType]").val();
		
		mygrid = new dhtmlXGridObject("divGridReportContainer");
		mygrid.setImagesPath("codebase/imgs/"); 
		mygrid.setHeader('Date,Branch,Transaction,Posted By,Sales No,Total Amount,'+
			'Payment Type,Card Charge, EWT,Cash Received,Status'); 
		mygrid.attachHeader(',#select_filter_strict,#select_filter_strict,#select_filter_strict,#text_filter,,'+
			'#select_filter_strict,,,,#select_filter_strict'); 
		mygrid.setInitWidths('150,200,200,150,150,150,150,150,150,150,150');
		mygrid.setColAlign('center,left,center,center,center,right,center,right,right,right,center');
		mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.load(URL+'views/report/grid_data/payment.php?'+
				'from_date='+strFromDate+'&'+
				'to_date='+strToDate+'&'+
				'branch_id='+intBranch+'&'+
				'view_type='+strViewType
			);
	});
	
	$('#btnExport').click(function() {
		URL = document.getElementById('hidUrl').value;
		mygrid.toExcel(URL+'public/grid-excel-php/generate.php');
	});
	
});

