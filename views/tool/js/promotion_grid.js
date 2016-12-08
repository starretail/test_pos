dhtmlxEvent(window,"load",function(){    	
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
});