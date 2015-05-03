$( function() {
	$('#AdminUser').submit( function() {
		if ( $('#id').val().length > 0 ) {
			if ( $('#pw').val() != $('#pwConfirm').val() ) {
				alert( 'password confirm not matched' );
			}
		}
	});
	$('#userid').change( function( ev ) {
		$('#duplicatedCheckResult li').hide();
		if ( ! $('#userid').val().match(/.{5,20}/) ) {
			$('#duplicatedCheckResult .notMatch').show();
			return false;
		}
		jQuery.ajax({
			url: $('#userid').attr('data-dup-check-href'),
			type: 'post',
			data: {
				userid: $('#userid').val()
			},
			success: function( result ) {
				if ( result > 0 ) {
					$('#duplicatedCheckResult .exists').show();
				} else {
					$('#duplicatedCheckResult .notExists').show();
				}
			}
		});
	});
});
