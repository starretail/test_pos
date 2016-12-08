$(function() {
	
	$('#btnView').click(function() {
		URL = document.getElementById('hidUrl').value;
		
		var intBranch = $("select[name=selBranch]").val();
		var strDate = $("input[name=txtDate]").val();
		var strViewType = $("select[name=selViewType]").val();
		
		if (strViewType == '2' || strViewType == '1') {
			mygrid = new dhtmlXGridObject("divGridReportContainer");
			mygrid.setImagesPath("codebase/imgs/"); 
			mygrid.setHeader("Branch,Item Category,Item Tag,Description,Pending Delivery,Actual Inventory,Total"); 
			mygrid.attachHeader("#select_filter_strict,#select_filter_strict,#select_filter_strict,#select_filter_strict,,,"); 
			mygrid.setInitWidths("150,200,200,200,120,120,120");
			mygrid.setColAlign("left,left,left,left,right,right,right");
			mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro');
			mygrid.setSkin("light");
			mygrid.init();
			mygrid.load(URL+'views/report/grid_data/inventory.php?'+
				'branch_id='+intBranch+'&'+
				'date='+strDate+'&'+
				'view_type='+strViewType
			);
		} else {
			mygrid = new dhtmlXGridObject("divGridReportContainer");
			mygrid.setImagesPath("codebase/imgs/"); 
			mygrid.setHeader("Branch,Item Category,Item Tag,Description,Quantity,Serial"); 
			mygrid.attachHeader("#select_filter_strict,#select_filter_strict,#select_filter_strict,#select_filter_strict,,"); 
			mygrid.setInitWidths("150,200,200,200,120,120");
			mygrid.setColAlign("left,left,left,left,right,right");
			mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro');
			mygrid.setSkin("light");
			mygrid.init();
			mygrid.load(URL+'views/report/grid_data/inventory.php?'+
				'branch_id='+intBranch+'&'+
				'date='+strDate+'&'+
				'view_type='+strViewType
			);
		}
	});
	
	$('#btnExport').click(function() {
		URL = document.getElementById('hidUrl').value;
		mygrid.toExcel(URL+'public/grid-excel-php/generate.php');
	});
	
});

