dhtmlxEvent(window,'load',function(){    	
	URL = document.getElementById('hidUrl').value;	
	mygrid = new dhtmlXGridObject('divGridReportContainer');
	mygrid.setImagesPath('codebase/imgs/'); 
	mygrid.setHeader('Id,Surname,First Name,Middle Name,Position,Username,'+
		'Branch,Branch Address,Landline No,Mobile No,Birthday,Address,Contact No,Date Hired,Status'); 
	mygrid.attachHeader(',#text_filter,#text_filter,#text_filter,#select_filter_strict,'+
		'#text_filter,#select_filter_strict,#text_filter,#text_filter,#text_filter,,#text_filter,#text_filter,,#select_filter_strict'); 
	mygrid.setInitWidths('50,150,150,150,150,150,150,200,150,150,150,200,150,150,150');
	mygrid.setColAlign('center,left,left,left,center,left,left,left,center,center,center,left,left,center,center');
	mygrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
	mygrid.setSkin('light');
	mygrid.init();
	mygrid.load(URL+'views/tool/grid_data/user_profile.php');
	mygrid.attachEvent('onRowDblClicked',function(rowId,colInd){
		getDataGridDetails(mygrid.cells(rowId,0).getValue());
	});
});