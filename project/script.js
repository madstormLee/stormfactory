$(function() {
	CKEDITOR.replace('contents', {
		extraPlugins: 'autogrow',
		allowedContent: true
	});

	$('#write').submit( function ( ev ) {
		if (! $('#title').val().length > 0) {
			alert('no contents');
			$('#title').focus();
			return false;
		}
	});
	$('#images').click( function( ev ) {
		if( ev.target.tagName != 'IMG' ) {
			return false;
		}
		var el = $(ev.target);
		$('#image').val( ev.target.src );
	});
});
