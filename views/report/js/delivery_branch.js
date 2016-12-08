$(function() {
	
	$('#btnView').click(function() {
		URL = document.getElementById('hidUrl').value;
		
		var intFromBranch = $("input[name=hidFromBranch]").val();
		var intToBranch = $("input[name=hidToBranch]").val();
		var strFromDate = $("input[name=txtFromDate]").val();
		var strToDate = $("input[name=txtToDate]").val();
		var strViewType = $("select[name=selViewType]").val();
		
		mygrid = new dhtmlXGridObject("divGridReportContainer");
		mygrid.setImagesPath("codebase/imgs/"); 
		mygrid.setHeader("Id,Origin,Delievery To,Delivery No,Delievery Date,Item,Qty Delivered,Qty Received,Status"); 
		mygrid.attachHeader(",#select_filter_strict,#select_filter_strict,#text_filter,,#select_filter_strict,,,#select_filter_strict"); 
		mygrid.setInitWidths("50,200,200,150,150,200,120,120,150");
		mygrid.setColAlign("center,left,left,center,center,left,right,right,center");
		mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro');
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.load(URL+'views/report/grid_data/delivery.php?'+
				'from_branch_id='+intFromBranch+'&'+
				'to_branch_id='+intToBranch+'&'+
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

