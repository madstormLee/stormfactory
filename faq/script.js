$( function() {
	var layoutHref = $('#index').attr('data-layout-href');
	if( layoutHref.length > 0 ) {
		$('#index').load( layoutHref );
	}
	$('#layout').click( function( ev ) {
		var href = ev.target.href;
		if ( href == undefined ) {
			return false;
		}
		$('#index').load( href );

		var layout = href.match(/file=(.*)/)[1];

		$.ajax({
			data: { layout: layout },
			url: $('#index').attr('data-save-href'),
			success: function( result ) {
				if( result !== '1' ) {
					alert( 'save error!' );
				}
			}
		});
		return false;
	});
});
