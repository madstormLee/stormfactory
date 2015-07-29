/************************** extension **************************/
Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
			c = isNaN(c = Math.abs(c)) ? 2 : c, 
			d = d == undefined ? "." : d, 
			t = t == undefined ? "," : t, 
			s = n < 0 ? "-" : "", 
			i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
			j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
/************************** jQuery extension **************************/
$.fn.extend({
	//### Prototype "up" method
	up: function(selector) {
		var found = "";
		selector = $.trim(selector || "");

		$(this).parents().each(function() {
			if (selector.length == 0 || $(this).is(selector)) {
				found = this;
				return false;
			}
		});

		return $(found);
	},
	down: function() {
		var el = this[0] && this[0].firstChild;
		while (el && el.nodeType != 1)
	el = el.nextSibling;
return $(el);
	}
});

function htmlEntities(str) {
	return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/******************* initialize *******************/
$( function() {
	$(document).foundation();

	$('[data-href]').each( function( num, unit ) {
		$(unit).load( $(unit).attr('data-href') );
	});

	$('a[data-target]').click( function( ev ) {
		$($(ev.target).attr('data-target')).load( ev.target.href );
		return false;
	});
	$('form[data-target]').submit( function( ev ) {
		$($(this).attr('data-target')).load( this.action, $(this).serialize() );
		return false;
	});

	$('a[data-confirm]').click( function( ev ) {
		if ( false === confirm( $(ev.target).attr('data-confirm') ) ) {
			return false;
		}
	});
	$('a[data-popup]').click( function( ev ) {
		var el = $(ev.target);
		window.open( this.href, el.attr('target'), el.attr('data-popup'));
		return false;
	});
	$('a.toggleId').click( function( ev ) {
		$( $(ev.target).attr('href') ).toggle();
		return false;
	});
	$('nav.tab a').click( function( ev ) {
		$(ev.target).addClass('current').siblings().removeClass('current');
		var target = $( $(ev.target).attr('href') );
		target.show().siblings().hide();
		return false;
	});
	/************************* form **************************/
	$('form input.ckAll').click( function( ev ) {
		$(ev.target).up('form').find('[type=checkbox]').attr('prop', this.checked );
	});
	$('button.back').click( function( ev ) {
		history.back();
	});
	$('form.changeSubmit').change( function( ev ) {
		$(ev.target).up('form').submit();
	});
	/********************** .widget **********************/
	$('.widget').click( function( ev ) {
		ev.preventDefault();
		var el = $(ev.target);
		var href = $(ev.target).attr('href')
		if ( href ) {
			el.up('.widget').load( href );
		}
	});
	$('.widget').submit( function( ev ) {
		ev.preventDefault();
		var form = $(ev.target);
		jQuery.ajax({
			url: form.attr('action'),
			type: form.attr('method')
			// todo: ...
		});
	});
});
