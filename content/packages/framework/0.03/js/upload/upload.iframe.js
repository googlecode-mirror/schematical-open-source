$(function(){
	
	$('#uplFile').change(function(){
		//Submit form
		$('#frmUplaod').submit().hide();
		
		//Display uploading
		$('#divMessage').html('<h3>Uploading</h3>');
	});
});