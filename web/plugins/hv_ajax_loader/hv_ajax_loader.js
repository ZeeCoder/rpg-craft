/**
 * HV Ajax Loader
 * @version: 1.0 - (2012/06/24)
 * @requires jQuery v1.2.2 or later 
 * @author Hubert Viktor
 
 * Licensed under MIT licence:
 * http://www.opensource.org/licenses/mit-license.php
**/

(function($) {
	
	var methods = {
		init: function(options) {
			/*Settings*/
			var s = $.extend({
				"anim_x": 100,
				"anim_y": 100,
				"toggle_on": null,
				"cover_on": true,
				"align": "center",
				"valign": "center",
				"loader_attach_class": null,
				"cover_attach_class": null
			}, options);
			
			return this.each(function() {
				var c_this = $(this);
				if (s.cover_on) {
					var cover = $("<div/>").addClass("hv-ajax-cover").appendTo(c_this);
					if (s.cover_attach_class!=null) cover.addClass(s.cover_attach_class);
				}
				var loader = $("<div/>")
					.addClass("hv-ajax-loader h"+s.align+" v"+s.valign)
					.appendTo(c_this);
				if (s.loader_attach_class!=null) loader.addClass(s.loader_attach_class);
				$(c_this).data("cover_on", s.cover_on);
			
				if (s.toggle_on!=null) {
					$(s.toggle_on).data("loader_visible", false);
					$(s.toggle_on).click(function(){
						c_this.hv_ajax_loader('toggle_loading');
					});
				}
				
			});
		},
		toggle_loading: function(onoff) {
			var visible = $(this).data("loader_visible");
			var cover_on = $(this).data("cover_on");
			if (onoff!=null) visible = ((onoff=="on")?false:true);
			
			if (visible) {
				$(this).children(".hv-ajax-loader").css({"display": "none"});
				if (cover_on) $(this).children(".hv-ajax-cover").css({"display": "none"});
			} else {
				$(this).children(".hv-ajax-loader").css({"display": "block"});
				if (cover_on) $(this).children(".hv-ajax-cover").css({"display": "block"});
			}
			
			$(this).data("loader_visible", !visible);
		}
	};
	$.fn.hv_ajax_loader = function(methodOrOptions) {
		if ( methods[methodOrOptions] ) {
			return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
			// Default to "init"
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
		}
	};

})(jQuery);