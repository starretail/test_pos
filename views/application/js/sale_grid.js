
$(function() {
	
	$('#btnSearch').click(function() {
		URL = document.getElementById('hidUrl').value;	
		intStockLocation = document.getElementById('selStockLocation').value;	
		strInvoiceNo = document.getElementById('txtInvoice').value;	
		mygrid = new dhtmlXGridObject("divGridReportContainer");
		mygrid.setImagesPath("codebase/imgs/"); 
		mygrid.setHeader("ID,Stock Location, Date,Invoice,Customer,Item,Qty,SRP,Cost,Profit,Serial,Payment Type,Amount"); 
		mygrid.setInitWidths("50,200,150,150,200,200,100,120,120,120,150,150,120");
		mygrid.setColAlign("center,left,center,center,left,left,right,right,right,right,center,center,right");
		mygrid.setColTypes('link,ron,ron,ron,ron,ron,ron,ron,ron,ron,ron,ron,ron');
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.load(URL+'views/application/grid_data/sale.php'+
			'?stock_location_id='+intStockLocation+'&invoice_no='+strInvoiceNo);
	});
	
});