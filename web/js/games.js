
	var ongoing_timer;
	//var ongoing_set;
/* Checking for ongoing games */
	function ongoing_games() {
		$.ajax({
			url: PAGE_ROOT+'call/ongoing_games.php'
			,dataType: 'json'
			,success: function(data) {
				$.each( data, function( id, active ) {
					$( '#ongoing_'+id ).removeClass('active');
					if ( active )
						$('#ongoing_'+id).addClass('active').click(function(){
							window.location = PAGE_ROOT+'game/?id='+id;
						});
					else
						$( '#ongoing_'+id ).unbind( 'click' );
				});
				//if ( ongoing_set )
					ongoing_timer = setTimeout( function(){
						ongoing_games();
					}, 2000 );
			}
		});
	}
		
/* Install new game. */
	function install_new_game() {
		$( '#game_install').hv_ajax_loader('toggle_loading', 'on' );
		$.ajax({
			type: 'POST'
			,url: PAGE_ROOT+'call/install_new_game.php'
			,data: $( '#game_install_form' ).serialize()
			,timeout: 2000
			,success: function( data ){
				if ( data == '1' )
					window.location.reload();
				else
					$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
						'message': c['error_sheet_install']
					});
			}
			,error: function() {
				$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
					'message': c['error_sheet_install_timeout']
				});
				$( '#game_install' ).hv_ajax_loader( 'toggle_loading', 'off' );
			}
		});
	}
	
/* Create new game. */
	function create_new_game() {
		$( '#newGame' ).hv_ajax_loader( 'toggle_loading', 'on' );
		$.ajax({
			type: 'POST'
			,url: PAGE_ROOT+'call/create_new_game.php'
			,data: {
				'type': $( '#cg_type' ).val(),
				'title': $( '#cg_title' ).val(),
				'description': $( '#cg_desc' ).val()
			}
			,timeout: 2000
			,success: function( data ){
				if ( parseInt( data ) >= 0 )
					window.location = PAGE_ROOT+'game/?id='+data;
				else
					$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
						'message': c['error_sheet_install']+data
					});
			}
			,error: function() {
				$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
					'message': c['error_sheet_install_timeout']
				});
				$( '#newGame' ).hv_ajax_loader( 'toggle_loading', 'off' );
			}
		});
	}

/* Show install game form. */
	var ng_show_toggle = false;
	function new_game_show_toggle() {
		if ( ng_show_toggle )
			$( '#the_black_cloud, #newGame' ).fadeOut( 200 );
		else
			$( '#the_black_cloud, #newGame' ).fadeIn( 200 );
			
		ng_show_toggle = !ng_show_toggle;
	}

/* Show create game form. */
	var cg_show_toggle = false;
	function create_game_show_toggle() {
		if ( cg_show_toggle )
			$( '#the_black_cloud, #game_install' ).fadeOut(200);
		else
			$( '#the_black_cloud, #game_install' ).fadeIn(200);
		
		cg_show_toggle = !cg_show_toggle;
	}
	
/* If the DOM's fully loaded. */
	$(function(e) {
		
		/* Setup ajax loading animation. */
			$( '#newGame, #game_install' ).hv_ajax_loader({
				'cover_on': true
			});
			
		/* Check ongoing games */
			ongoing_games()
			
	});