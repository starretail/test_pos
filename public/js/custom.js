 $( function() {
	$( ".datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
    });
	$( ".datepicker" ).val(new Date().toJSON().slice(0,10));
	
	
	$('#btnClear').click(function() {
		$(".divContinerInputDetails input").val("");
		$(".divContinerInputDetails select").val("");
		$(".divContinerInputDetails textarea").val("");
	});
	
	
	$('#btnExport').click(function() {
		URL = document.getElementById('hidUrl').value;
		mygrid.toExcel(URL+'public/grid-excel-php/generate.php');
	});
	
	
	$('#btnSaveAsNew').click(function() {
		var blnConfirm = confirm("Are you sure you want to save this as new?");
		return blnConfirm;
	});
	
	
	$('#btnUpdate').click(function() {
		var blnConfirm = confirm("Are you sure you want to update this?");
		return blnConfirm;
	});
	
	
	$('#btnDelete').click(function() {
		var blnConfirm = confirm("Are you sure you want to delete this?");
		return blnConfirm;
	});
	
	
	$('#btnCancel').click(function() {
		var blnConfirm = confirm("Are you sure you want to cancel this transaction?");
		return blnConfirm;
	});
});