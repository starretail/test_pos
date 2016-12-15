dhtmlxEvent(window,"load",function(){    	
	URL = document.getElementById('hidUrl').value;	
	
	mygrid = new dhtmlXGridObject("divGridReportContainer");
	mygrid.setImagesPath("codebase/imgs/"); 
	mygrid.setHeader("Id,Date of Deposit,Account No,Account Name,Deposit Amount,Deposited By"); 
		mygrid.attachHeader(",#text_filter,#text_filter,#text_filter,#text_filter,#text_filter");  
		mygrid.setInitWidths("50,150,150,150,150,150");
		mygrid.setColAlign("center,center,center,center,left,left");
		mygrid.setColTypes('ro,ro,ro,ro,ro,ro');
	mygrid.setSkin("light");
	mygrid.init();
	
});