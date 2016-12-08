dhtmlxEvent(window,"load",function(){    	
	URL = document.getElementById('hidUrl').value;	
	mygrid = new dhtmlXGridObject("divGridReportContainer");
	mygrid.setImagesPath("codebase/imgs/"); 
	mygrid.setHeader("Id,Name,Building and Street,Barangay,City/District,Province,Landlibe No.,Mobile No.,Date Active,Status"); 
	mygrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,,#select_filter_strict"); 
	mygrid.setInitWidths("50,120,150,150,150,150,150,150,120,100");
	mygrid.setColAlign("center,left,left,left,left,left,left,left,center,center");
	mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
	mygrid.setSkin("light");
	mygrid.init();
	mygrid.load(URL+'views/tool/grid_data/branch.php');
	mygrid.attachEvent("onRowDblClicked",function(rowId,colInd){
		getDataGridDetails(mygrid.cells(rowId,0).getValue());
	});
});