dhtmlxEvent(window,"load",function(){    	
	URL = document.getElementById('hidUrl').value;	
	mygrid = new dhtmlXGridObject("divGridReportContainer");
	mygrid.setImagesPath("codebase/imgs/"); 
	mygrid.setHeader("Id,Origin,Delievery To,Delivery No,Item,Qty Delivered,Qty Received,Status"); 
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
});