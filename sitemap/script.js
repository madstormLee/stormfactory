var MadSitemap = function() {
	this.init= function(){
		$$('.addMethod').each(function(unit){
			unit.observe('click',MadSitemap.addMethod);
		});
		$('addClass').observe('click',MadSitemap.addClass);
		$('newMethod').observe('focus',function(event){
			event.element().value='';
		});
		$('formAddMethod').observe('submit',function(event){
			return false;
		});
	};
	this.addClass= function( event ) {
		$('formAddMethod').hide();
		var el = $('formAddClass').show();
		el.style.left = Event.pointerX(event) -220 + 'px';
		el.style.top = Event.pointerY(event) -10 + 'px';
	};
	this.addMethod= function( event ) {
		$('formAddClass').hide();
		$('className').value = event.element().previous('.controller').innerHTML;
		var el = $('formAddMethod').show();
		el.style.left = Event.pointerX(event) -220 + 'px';
		el.style.top = Event.pointerY(event) -10 + 'px';
	};
};

var Controllers = function() {
	// initialize
	var me = this;
	this.sitemap = new Sitemap;

	// events assign
	$('#controllers').click( function( ev ) {
		ev.preventDefault();
		me.sitemap.add( $(ev.target) );
	});
};

var SitemapWrite = function() {
	var me = this;
	this.container = $('#SitemapWrite');

	this.container.submit( function( ev ) {
		ev.preventDefault();
		var target = $(ev.target);
		$('#content').val( $('#SitemapIndex .content').html() );
		jQuery.ajax({
			url: target.attr('action'),
			type: target.attr('method'),
			data: target.serialize(),
			success: function( result ) {
				$('#console').html( result );
			}
		});
	});
}

var Sitemap = function() {
	// initialize
	var me = this;
	this.container = $('#SitemapIndex');
	this.current = this.container.find('dt.root');
	// assign events
	this.container.click( function ( ev ) {
		ev.preventDefault();
		var el = $(ev.target);
		if ( ( el.hasClass('hasDic') || el.hasClass('hasValue') ) && el.html() != 'subs' ) {
			me.container.find('.current').removeClass('current');
			el.addClass('current');
			me.current = el;

			$('#floatMenu').css({
				top: el.position().top
			});
		}
	});
	$('#remove').click( function( ev ){
		me.remove( $(ev.target) );
	})
	$('#addSub').click( function( ev ){
		alert( 'not yet' );
	})
	this.container.dblclick( function ( ev ) {
		ev.preventDefault();
		var el = $(ev.target);
		var input = $("<input type='text' value='"+el.html()+"' />");
		input.blur( function( ev ) {
			$(ev.target).up().html( $(ev.target).val() );
		});
		input.keypress( function( ev ) {
			var code =  (ev.keyCode ? ev.keyCode : ev.which);
			if ( code == 13 ) {
				this.blur();
			}
		});
		el.html( input );
		input.select();
	});

	// methods
	this.remove = function( el ) {
		if ( me.current.hasClass('root') ) {
			alert('you cannot remove root');
			return false;
		}
		me.current.next('dd').remove();
		me.current.remove();
	};

	this.add = function ( el ) {
		if ( ! me.hasSubs() ) {
			me.createSub();
		}
		var domain = el.html().charAt(0).toLowerCase() + el.html().slice(1);
		var value = el.html();
		me.getSubs().append( $('<dt class="hasDic">' + domain + '</dt><dd class="dic"><dl><dt class="hasValue">controller</dt><dd class="value">' + value + '</dd></dl></dd>') );
	};
	this.hasSubs = function() {
		var rv = false;
		me.current.next('dd').find('.hasDic').each( function( num, unit ) {
			if ( unit.innerHTML == 'subs' ) {
				rv = true;
				return false;
			}
		});
		return rv;
	};
	this.getSubs = function() {
		var rv = undefined;
		me.current.next('dd').find('.hasDic').each( function( num, unit ) {
			if ( unit.innerHTML == 'subs' ) {
				rv = $(unit).next('dd').children('dl');
				return false;
			}
		});
		return rv;
	};
	this.createSub = function() {
		if ( me.current.next('dd').children('dl').length == 0 ) {
			me.current.next('dd').append( $('<dl></dl>') );
		}
		var target = me.current.next('dd').children('dl');
		target.append( $('<dt class="hasDic">subs</dt><dd class="dic"><dl></dl></dd>') );
	};
}

// initialize 
$( function() {
	var controllers = new Controllers;
	var sitemapWrite = new SitemapWrite;
});

// from new sitemap;
$( function() {
	$('#AddKey').submit( function( ev ) {
		ev.preventDefault();
		var template = $('#template').val();
		var $key = $('#key').val();
		var $value = $('#value').val()
		var row = template
			.replace(/\$key/g, $key )
			.replace( /\$value/g, $value );
		$('#keys').append( row )
	});

	$('#keys').click( function(ev) {
		ev.preventDefault();
		var el = $(ev.target);
		var href = el.attr('href');
		if ( href == '#remove' ) {
			el.up('.row').remove();
		}
	});
	$('[data-load]').each( function() {
		$(this).load( $(this).attr('data-load') );
	});
});

