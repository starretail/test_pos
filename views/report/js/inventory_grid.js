dhtmlxEvent(window,"load",function(){    	
	URL = document.getElementById('hidUrl').value;	
	mygrid = new dhtmlXGridObject("divGridReportContainer");
	mygrid.setImagesPath("codebase/imgs/"); 
	mygrid.setHeader("Branch,Item Category,Item Tag,Description,Pending Delivery,Actual Inventory,Total"); 
	mygrid.attachHeader("#select_filter_strict,#select_filter_strict,#select_filter_strict,#select_filter_strict,,,"); 
	mygrid.setInitWidths("150,200,200,200,120,120,120");
	mygrid.setColAlign("left,left,left,left,right,right,right");
	mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro');
	mygrid.setSkin("light");
	mygrid.init();
	
});