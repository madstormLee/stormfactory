$( function() {
	$('#layout').click( function( ev ) {
		if ( ev.target.href == undefined ) {
			return false;
		}
		$('#index').load( ev.target.href );
		return false;
	});
});
