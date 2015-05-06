var ContentsWrite = function() {
	var my = this;
	my.mock = $('#write');

	this.onType = function( ev ) {
		var el = $(ev.target);
		var current = $('[name=type]:checked');
		if ( current.val() != 'link' ) {
			var target = current.next().next();
			target.click();
			el.html( target.val() );
		} else {
			var target = $('[name=type]:first');
			target.click();
			el.html( target.val() );
		}
		return false;
	};

	this.onFloat = function( ev ) {
		if ( my.mock.css('float') == 'left' ) {
			my.mock.css('float', 'right') ;
			$(ev.target).html('&gt;');
			$('#float').val('right');
		} else {
			my.mock.css('float', 'left') ;
			$(ev.target).html('&lt;');
			$('#float').val('left');
		}
		return false;
	};

	this.onScale = function( ev ) {
		// todo: use scale to include medium-X small-X. scale: 1-4
		if ( my.mock.hasClass('scale-1') ) {
			my.mock.attr('class', 'scale-2 medium-6 small-12');
			$('#scale').val('2');
		} else {
			my.mock.attr('class', 'scale-1 medium-3 small-6');
			$('#scale').val('1');
		}
		return false;
	};

	this.onTie = function( ev ) {
		var el = $(ev.target);
		if ( el.hasClass('tie') ) {
			el.attr('class','tied');
		} else {
			el.attr('class','tie');
		}
		return false;
	};

	this.onImage = function( ev ) {
		if( ev.target.tagName != 'IMG' ) {
			return false;
		}
		var el = $(ev.target);
		var src = el.next('a').attr('data-resource');

		$('#write a.image img').attr( 'src', el.next('a').attr('data-src') );
		$('#image').val( src );
	};

	this.onContents = function( ev ) {
		$('#modal').html( $('form.write') );
		var current = $('[name=type]:checked');
		if ( current.val() == 'article' ) {
			CKEDITOR.replace('contents', {
				extraPlugins: 'autogrow',
				allowedContent: true
			});
		}
	};

	$('#write a.type').click( this.onType );
	$('#write a.float').click( this.onFloat);
	$('#write a.scale').click( this.onScale );
	$('#write a.tie').click( this.onTie );
	$('#write a.contents').click( this.onContents );
	$('#images').click( this.onImage );
};

$(function() {
	new ContentsWrite;

	$('a[data-target]').click( function( ev ) {
		$($(this).attr('data-target')).load( this.href );
		return false;
	});
});
