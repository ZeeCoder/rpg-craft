
(function($) {
	
	var methods = {
		init: function( options ) {
			/*Settings*/
			var s = $.extend({
				'fade_in_dur': 200
				,'fade_out_dur': 200
			}, options);
			
			return this.each(function() {
				var o = $( this );
				o.data( 's', s );
				
				o.find( 'div.cancel' ).click( function(){
					o.hv_alert( 'cancel' );
				} );
				
			});
		}
		,cancel: function() {
			var o = $( this );
			var s = o.data( 's' );
			
			$( this ).fadeOut( s.fade_out_dur );
		}
		,show_alert: function( options ){
			var o = $( this );
			var obj_s = o.data( 's' );
			
			var s = $.extend({
				'title': 'Info'
				,'type': 'info'
				,'message': null
				,'ok_btn': false
				,'duration': obj_s.fade_in_dur
			}, options);
			
			o = $( this );
			
			o.find( 'div.title' ).html( s.title );
			o.find( 'div.content' ).html( s.message );
			
			o.attr( 'class', '' ).addClass( s.type );
			
			if ( s.ok_btn )
				o.find( 'div.ok' )
					.attr( 'class', '' ).addClass( 'button ok' )
					.unbind( 'click' ).click(function(){
						o.hv_alert( 'cancel' );
						setTimeout( function(){
							s.ok_btn()
						}, obj_s.fade_out_dur);
					});
			
			
			o.fadeIn( s.duration );
			
		}
	};
	$.fn.hv_alert = function(methodOrOptions) {
		if ( methods[methodOrOptions] ) {
			return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
			// Default to "init"
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist.' );
		}
	};
	/*
	var f = function() {
		alert( 'asd' );
	};

	$('#hv_alert_wrapper').hv_alert();
	$('#hv_alert_wrapper').hv_alert('show_alert', {
		'message': 'Zee Mighty Message'
		,'ok_btn': f
	});
	*/
	
})(jQuery);