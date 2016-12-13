dhtmlxEvent(window,'load',function(){    	
	URL = document.getElementById('hidUrl').value;	
	mygrid = new dhtmlXGridObject('divGridReportContainer');
	mygrid.setImagesPath('codebase/imgs/'); 
	mygrid.setHeader('Date,Branch,Transaction,Posted By,Sales No,Total Amount,'+
			'Payment Type,Card Charge, EWT,Cash Received,Status'); 
	mygrid.attachHeader(',#select_filter_strict,#select_filter_strict,#select_filter_strict,#text_filter,,'+
		'#select_filter_strict,,,,#select_filter_strict'); 
	mygrid.setInitWidths('150,200,200,150,150,150,150,150,150,150,150');
	mygrid.setColAlign('center,left,center,center,center,right,center,right,right,right,center');
	mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
	mygrid.setSkin('light');
	mygrid.init();
	
});