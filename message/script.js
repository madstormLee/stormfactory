$( function() {
	$('#receiver').change( function() {
		var el = $(this);

		$.ajax({
			url: el.attr('data-exists-href'),
			data: {
				userId: el.val()
			},
			success: function( result ) {
				if ( result != '1' ) {
					alert( 'user not exists');
					el.focus();
				}
			}
		});
	});
});
