$(document).ready(function(){
	var request = {phon: ''};
	var rc_loaded = setInterval(function(){
		var rc_isset = $('#rc-phone-input').attr('class') || $('#rc-popup-form-input').attr('class') || false;
		if( rc_isset ){
			clearInterval( rc_loaded );
			console.log('done js');
			init_rc();
		}
	},500);
	function init_rc(){
		$('#rc-phone-button').click(function(){
			console.log('#rc-phone-button');
			request.phon = $('#rc-phone-input').val() || '';
			$.ajax({
				url: 'http://www.sailid.ru/rconnect/handler.php',
				data: 'data='+JSON.stringify( request ),
				type: 'POST', 
				success: function(text){
					console.log(text);
				}
			});
		})
		$('#rc-popup-form-button').click(function(){
			console.log('#rc-popup-form-button');
			request.phon = $('#rc-popup-form-input').val() || '';
			jQuery.ajax({
				url: 'http://www.sailid.ru/rconnect/handler.php',
				data: 'data='+JSON.stringify( request ),
				type: 'POST', 
				success: function(text){
					console.log(text);
				}
			});
		})
	}
});