dhtmlxEvent(window,'load',function(){    	
	URL = document.getElementById('hidUrl').value;	
	mygrid = new dhtmlXGridObject('divGridReportContainer');
	mygrid.setImagesPath('codebase/imgs/'); 
	mygrid.setHeader('Date Sold,Branch,Transaction,Posted By,Sales No,Imei,Item Category,Item Tag,Item Description,'+
			'Quantity,Selling Price,Discount,Net Sales,Subtotal'); 
	mygrid.attachHeader(',#select_filter_strict,#select_filter_strict,#select_filter_strict,#text_filter,#text_filter,'+
		'#select_filter_strict,#select_filter_strict,#select_filter_strict,,,,,,#select_filter_strict,,,,#select_filter_strict'); 
	mygrid.setInitWidths('150,200,200,200,150,150,150,200,200,100,120,120,120,120,120');
	mygrid.setColAlign('center,left,center,center,center,center,center,left,left,right,right,right,right,right,');
	mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
	mygrid.setSkin('light');
	mygrid.init();
	
});