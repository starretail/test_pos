dhtmlxEvent(window,"load",function(){    	
	var URL = document.getElementById('hidUrl').value;	
	
	
	mygrid = new dhtmlXGridObject("divGridReportContainer");
	mygrid.setImagesPath("codebase/imgs/"); 
	mygrid.setHeader("Id,Date of Deposit,Account No,Account Name,Deposit Amount,Deposited By"); 
	mygrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"); 
	mygrid.setInitWidths("50,150,150,150,150,200");
	mygrid.setColAlign("center,center,center,center,right,center");
	mygrid.setColTypes('ro,ro,ro,ro,ro,ro');
	mygrid.setSkin("light");
	mygrid.init();
	mygrid.load(URL+'views/application/grid_data/deposit.php');
	mygrid.attachEvent("onRowDblClicked",function(rowId,colInd){
		getDataGridDetails(mygrid.cells(rowId,0).getValue());
	});
});