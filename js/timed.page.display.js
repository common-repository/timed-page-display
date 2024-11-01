jQuery(document).ready( function($){ 
	$('.input-timepicker').timepicker({
	    timeFormat: 'h:mm p',
	    interval: 30,
	    dynamic: false,
	    dropdown: true,
	    scrollbar: true
	});
}); 