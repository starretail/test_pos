dhtmlxEvent(window,"load",function(){    	
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
});