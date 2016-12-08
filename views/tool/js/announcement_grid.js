dhtmlxEvent(window,"load",function(){    	
	URL = document.getElementById('hidUrl').value;	
	mygrid = new dhtmlXGridObject("divGridReportContainer");
	mygrid.setImagesPath("codebase/imgs/"); 
	mygrid.setHeader("Id,Subject,Message,Date,Time,Create By"); 
	mygrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"); 
	mygrid.setInitWidths("50,150,200,150,150,150");
	mygrid.setColAlign("center,center,center,center,center,center");
	mygrid.setColTypes('ro,ro,ro,ro,ro,ro');
	mygrid.setSkin("light");
	mygrid.init();
	mygrid.load(URL+'views/tool/grid_data/announcement.php');
	mygrid.attachEvent("onRowDblClicked",function(rowId,colInd){
		getDataGridDetails(mygrid.cells(rowId,0).getValue());
	});
});