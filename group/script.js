$( function(){
	$('#index').click( function( ev ) {
		if ( ev.target.href == undefined ) {
			return false;
		}
		var el = $(ev.target);
		if ( el.hasClass('list') ) {
			if ( el.html() == '-' ) {
				el.html('+');
			} else {
				el.html('-');
			}

			if( el.up('dt').next().html().length == 0 ) {
				el.up('dt').next().load( el.attr('href') );
			} else {
				el.up('dt').next().html( '' );
			}
		} else if ( el.hasClass( 'write' ) ) {
			$(el.attr('data-target')).load( el.attr('href') );
		}
		return false;
	});
});
