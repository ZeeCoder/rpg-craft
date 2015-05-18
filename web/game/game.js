
/* Global variables */
	var sync = true;
	var sync_to_manual = false; //Switch to manual refreshing.
	var sync_timer; //Timer variable for syncronising.
	var last_sync = 0;
	var first_sync = true;
	
/* Get microtime */
	function micro_time() {
		
		return ( new Date().getTime() );
		
	}
	
/*
	Syncronise data:
		- in-game users
		- entries
		- maps
		- inviteable friends
*/
	function sync_data(){
		
		clearTimeout( sync_timer );
		var ping_start = micro_time();
		$.ajax({
			url: 'sync.php',
			timeout: 10000,
			dataType: 'json',
			type: 'POST',
			data: {
				'first_sync': first_sync
			},
			success: function( data ){
				
				/* in-game users */
					check_in_game_users( data.in_game_users );
				
				/* Entry-listing */
					if ( typeof data.entry_list != 'undefined' && data.entry_list.length!=0 ) {
						list_entries( data.entry_list );
					}
				
				/* Map-listing */
					if ( typeof data.map_list != 'undefined' && data.map_list.length!=0 ) {
						if ( first_sync ) {
							list_maps( data.map_list, true );
							first_sync = false;
						} else
							list_maps( data.map_list );
					}
				
				/* inviteable_friends */
					if ( act_user_is_gm ) {
						manage_inviteables( data.inviteable_friends );
					}
				
			},
			complete: function(){
				first_sync = false;
				var ping_stop = micro_time();
				$( '#ping_wrapper div.ping_container' ).html( ping_stop-ping_start );
				
				if ( !sync_to_manual )
					sync_timer = setTimeout( function(){
						sync_data();
					}, 3000 );
			}
		});
		
	}
	
/* Maintaining inviteable friend list.  */
	function manage_inviteables( inviteable_list ){
		
		$( '#friends_to_invite div.not_invited_yet div, #friends_to_invite div.already_invited div' )
			.addClass( 'not_inviteable_del' );
		
		$.each( inviteable_list, function(i, list) {
			var id = parseInt( list.id );
			var name = list.name;
			var invited = list.invited;
			
			var act_obj = $( '#inviteable_one_'+id );
			if ( typeof act_obj != 'undefined' && act_obj.length > 0 ) {
				act_obj.removeClass( 'not_inviteable_del' );
				if ( !invited )
					if ( typeof $( 'div.already_invited div#inviteable_one_'+id ) != 'undefined' && $( 'div.already_invited div#inviteable_one_'+id ).length > 0 ) {
						act_obj.fadeOut( 50, function(){
							act_obj.appendTo( '#friends_to_invite div.not_invited_yet' ).fadeIn( 50 );
						} );
						act_obj.data( 'invited', false );
					}
			} else
				$( '<div/>' )
					.attr( 'id', 'inviteable_one_'+id )
					.appendTo( '#friends_to_invite div.not_invited_yet' )
					.html( name )
					.data( 'invited', false )
					.click( function(){
						var o_this = $( this );
						if (!o_this.data( 'invited' )) {
							o_this.fadeOut( 50, function(){
								o_this.appendTo( '#friends_to_invite div.already_invited' ).fadeIn( 50 );
							});
							
							$.ajax({
								type: 'POST',
								url: 'send_invite.php',
								data: {
									'id': id
								},
								success: function( data ) {
									if ( data!=0 ) {
										$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
											'message': c['error_invite']+' '+data
										});
										o_this.fadeOut( 50 );
									} else
										o_this.data( 'invited', true );
								},
								error: function() {
									$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
										'message': c['error_invite']+' '+data
									});
									o_this.fadeOut( 50 );
								}
							});
						}
					});
		});
		$( '.not_inviteable_del' ).fadeOut( 50, function(){
			$( this ).remove();
		} );
		
	}
	
/* Toggle inviteable friends window. */
	var inviteable_open = false;
	function show_inviteables() {
		
		if ( !inviteable_open )
			$( '#friends_to_invite' ).fadeIn( 200 );
		else
			$( '#friends_to_invite' ).fadeOut( 200 );
		
		inviteable_open = !inviteable_open;
		
	}
	
/* Show game description. */
	var desc_open = false;
	function show_desc( page ) {
		
		$( '#desc_container' ).fadeOut( 200 );
		if ( !desc_open ) {
			setTimeout( function(){
				$( '#desc_container div.desc_cont' ).css( 'display', 'none' );
				$( '#desc_container div.'+page ).css( 'display', 'block' );
				$( '#desc_container' ).fadeIn( 200 );
			}, 200 );
		}
		desc_open = !desc_open;
		
	}
	
/* Update actual user time in-game. */
	var update_in_game_user_timer;
	function update_in_game_user() {
		
		$.ajax({
			url: 'update_in_game_user.php',
			timeout: 10000,
			success: function( data ){
				if ( data==0 )
					update_in_game_user_timer = setTimeout( function(){
						update_in_game_user();
					}, 1000 );
				else
					$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
						'message': c['error_default_session_error']+' '+data
					});
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ) {
				update_in_game_user();
			}
		});
		
	}
	
/* Get the last time entries were updated, list them if needed. */
	var last_entry;
	function get_last_entry() {
		
		$.ajax({
			url: 'get_last_entry.php',
			timeout: 10000,
			success: function( data ){
				if ( data!=0 ) {
					data = parseInt( data );
					if ( typeof last_entry == 'undefined' || last_entry < data ) {
						last_entry = data;
						list_entries();
					}
				}
				setTimeout( function(){
					get_last_entry();
				}, 2000 );
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ) {
				setTimeout( function(){
					get_last_entry();
				}, 2000 );
			}
		});
		
	}
	
/* Get the last time maps were updated, list them if needed. */
	var last_map;
	function get_last_map() {
		
		$.ajax({
			url: 'get_last_map.php',
			timeout: 10000,
			success: function( data ){
				if ( data!=0 ) {
					data = parseInt( data );
					if ( typeof last_map == 'undefined' || last_map < data ) {
						last_map = data;
						list_maps();
					}
				}
				setTimeout( function(){
					get_last_map();
				}, 2000 );
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ) {
				setTimeout( function(){
					get_last_map();
				}, 2000 );
			}
		});
		
	}

/* Maintain ingame users. */
/* !!!Way too redundant, needs to be updated!!! */
	var disconnecting_count = new Array();
	function check_in_game_users( user_list ) {
		
		if ( user_list==null ) {
			$.ajax({
				url: 'check_in_game_users.php',
				timeout: 10000,
				dataType: 'json',
				success: function( data ){
					var datenow = new Date();
					datenow = parseInt( ( datenow.getTime()/1000 ) );
					$.each( data.posts, function( i ) {
						var id = parseInt( data.posts[i].usersID );
						var active = (datenow-10) <= parseInt( data.posts[i].time );
						var not_joined = (datenow-300) >= parseInt( data.posts[i].time );
						var spectator = data.posts[i].spectator == '1';
						var div;
						var need_click_setup = false;
						
						if ( not_joined )
							disconnecting_count[id] = 0;
						if ( active == false ) {
							if ( typeof disconnecting_count[id] == 'undefined' )
								disconnecting_count[id] = 10;
							if ( disconnecting_count[id] <= 0 ) {
								if (gm_id == id && loaded==true) {
									
								}
							} else {
								$( '#in_game_user_c_'+id ).html( ' ('+disconnecting_count[id]+')' );
								disconnecting_count[id]--;
							}
						} else {
							disconnecting_count[id] = 10;
							$( '#in_game_user_c_'+id ).empty();
							if ( gm_id == id ) {
								if ( $( '#loading_screen div.gm_offline' ).css('display') != 'none')
									$( '#loading_screen div.gm_offline' ).fadeOut( 200, function(){
										$( '#loading_screen').fadeOut( 500 );
									} );
							}
						}
						
						if ( typeof $( '#in_game_user_'+id ) != 'undefined' && $( '#in_game_user_'+id ).length == 0 ) {
							need_click_setup = true;
							$( '<div/>' ).attr( 'id', 'in_game_user_'+id ).addClass( 'gamer' ).appendTo( '#gamersWrapper div.gamers_content' ).css( 'opacity', 0 );
							$( '#in_game_user_'+id ).html( (( spectator == false )?'':'(S) ')+((gm_id == id)?'(KM) ':'')+data.posts[i].name );
							$( '<span/>' ).attr( 'id', 'in_game_user_c_'+id ).appendTo( '#in_game_user_'+id );
							
							if ( act_user_id == id )
								$( '#in_game_user_'+id ).css( 'text-decoration', 'underline' );
							
							if ( active == false )
								$( '#in_game_user_'+id ).fadeTo( 200, 0.5 );
							else
								$( '#in_game_user_'+id ).fadeTo( 200, 1 );
						} else {
							if ( active == true && $( '#in_game_user_'+id ).css( 'opacity' ) == 0.5 ) {
								height = $( '#in_game_user_'+id ).css( 'height', 'auto' ).height();
								$( '#in_game_user_'+id ).fadeTo( 200, 1 );
							} else if ( active == false && disconnecting_count[id] <= 0 && $( '#in_game_user_'+id ).css( 'opacity' ) == 1 )
								$( '#in_game_user_'+id ).fadeTo( 200, 0.5 );
							else if ( active == false && disconnecting_count[id] <= 0 )
								$( '#in_game_user_c_'+id ).empty();
						}
						if ( spectator == false && need_click_setup == true && gm_id != id ) {
							$( '#in_game_user_'+id ).click( function( e ) {
								$( '#character_sheet div.content' ).empty();
								$( '#charsheet_wrapper' ).hv_ajax_loader( 'toggle_loading', 'on' );
								$( '#character_sheet' ).fadeIn( 100, function(){
									$.ajax({
										type: 'POST'
										,url: '../charsheet_xml/index.php'
										,data: {
											'user_id': id
										}
										,timeout: 30000
										,success: function( data ) {
											$( '#character_sheet div.content' ).html( data );
										}
										,error: function( XMLHttpRequest, textStatus, errorThrown ) {
											$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
												'message': c['error_charsheet_loading']+' '+errorThrown+' '+textStatus
											});
										}
										,complete: function(){
											$( '#charsheet_wrapper' ).hv_ajax_loader( 'toggle_loading', 'off' );
										}
									});
								});
							});
						}
						if ( spectator == false && gm_id != id )
							$( '#in_game_user_'+id ).addClass( 'to_hover' );
					});
					setTimeout( function(){
						check_in_game_users();
					}, 2000 );
				},
				error: function( XMLHttpRequest, textStatus, errorThrown ) {
					setTimeout( function(){
						check_in_game_users();
					}, 2000 );
				}
			});
		} else {
			$.each( user_list, function( i, list ) {
				var id = parseInt( list.usersID );
				var active = ( list.active==='true' );
				var not_joined = active;
				var spectator = ( list.spectator==='true' );
				var div;
				var need_click_setup = false;
				
				
				if ( not_joined )
					disconnecting_count[id] = 0;
				if ( active == false ) {
					if ( typeof disconnecting_count[id] == 'undefined' )
						disconnecting_count[id] = 10;
					if ( disconnecting_count[id] <= 0 ) {
						if ( gm_id == id && loaded==true ) {
							
						}
					} else {
						$( '#in_game_user_c_'+id ).html( ' ('+disconnecting_count[id]+')' );
						disconnecting_count[id]--;
					}
				} else {
					disconnecting_count[id] = 10;
					$( '#in_game_user_c_'+id ).empty();
					if ( gm_id == id ) {
						if ( $( '#loading_screen div.gm_offline' ).css( 'display' ) != 'none' )
							$( '#loading_screen div.gm_offline' ).fadeOut( 200, function(){
								$( '#loading_screen' ).fadeOut( 500 );
							} );
					}
				}
				
				if ( typeof $( '#in_game_user_'+id ) != 'undefined' && $( '#in_game_user_'+id ).length == 0 ) {
					need_click_setup = true;
					$( '<div/>' )
						.attr( 'id', 'in_game_user_'+id )
						.addClass( 'gamer' )
						.appendTo( '#gamersWrapper div.gamers_content' )
						.css( 'opacity', 0 );
					$( '#in_game_user_'+id ).html( (( spectator == false )?'':'(S) ')+((gm_id == id)?'(KM) ':'')+list.name );
					$( '<span/>' ).attr('id', 'in_game_user_c_'+id).appendTo('#in_game_user_'+id);
					
					if ( act_user_id == id )
						$( '#in_game_user_'+id ).css( 'text-decoration', 'underline' );
						
					if ( active == false )
						$( '#in_game_user_'+id ).fadeTo( 200, 0.5 );
					else
						$( '#in_game_user_'+id ).fadeTo( 200, 1 );
				} else {
					if ( active == true && $( '#in_game_user_'+id ).css( 'opacity' ) == 0.5 ) {
						height = $( '#in_game_user_'+id ).css( 'height', 'auto' ).height();
						$( '#in_game_user_'+id ).fadeTo( 200, 1 );
					} else if ( active == false && disconnecting_count[id] <= 0 && $( '#in_game_user_'+id ).css( 'opacity' ) == 1 )
						$( '#in_game_user_'+id ).fadeTo( 200, 0.5 );
					else if ( active == false && disconnecting_count[id] <= 0 )
						$( '#in_game_user_c_'+id ).empty();
				}
				if ( spectator == false && need_click_setup == true && gm_id != id ) {
					$( '#in_game_user_'+id ).click( function( e ) {
						$( '#character_sheet div.content' ).empty();
						$( '#charsheet_wrapper' ).hv_ajax_loader( 'toggle_loading', 'on' );
						$( '#character_sheet' ).fadeIn( 200, function(){
							$.ajax({
								type: 'POST'
								,url: '../charsheet_xml/index.php'
								,data: {
									'user_id': id
								}
								,timeout: 10000
								,success: function( data ) {
									$( '#character_sheet div.content' ).html( data );
								}
								,error: function( XMLHttpRequest, textStatus, errorThrown ) {
									$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
										'message': c['error_charsheet_loading']+' '+errorThrown+' '+textStatus
									});
								}
								,complete: function(){
									$( '#charsheet_wrapper' ).hv_ajax_loader( 'toggle_loading', 'off' );
								}
							});
						});
					});
				}
				if ( spectator == false && gm_id != id )
					$( '#in_game_user_'+id ).addClass( 'to_hover' );
			});
		}
		
	}

/* Change the map image's src. */
	var first_src_change = true;
	function change_map_src( url ) {
		
		if ( $( '#mapimg img' ).attr( 'src' )==url )
			return;
			
		$( '#map div.container' ).hv_ajax_loader( 'toggle_loading', 'on' );
		if ( url=='default' )
			url = '../img/main_bg.jpg';
		$( '#mapimg img' ).attr( 'src', url );
		if ( first_src_change ) {
			$( '#mapimg img' ).load( function(){
				var new_w = ( url=='default' ) ? ( $( '#map' ).width()-2 ) : ( $( '#mapimg' ).width()-2 );
				var new_h = ( url=='default' ) ? ( $( '#map' ).height()-2 ) : ( $( '#mapimg' ).height()-2 );
				$( '#mapimg img' ).width( new_w );
				$( '#mapimg img' ).height( new_h );
				$( '#map div.container' ).hv_ajax_loader( 'toggle_loading', 'off' );
			});
			first_src_change=false;
		}
		
	}

/* GM logout from the game. */
/* !!!Depricated, not needed anymore!!! */
	function game_logout() {
		
		$.ajax({
			url: 'logout.php',
			success: function( data ) {
				if ( data=='0' )
					window.location = REDIRECT_TO;
				else
					$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
						'message': c['error_gm_exit']+' '+data
					});
			}
		});
		
	}
	
/* List new chat entries. */
	var scrollFirst = true;
	function list_chat(){
		
		$.ajax({
			url: 'list_chat.php'
			,timeout: 10000
			,dataType: 'json'
			,success: function(data){
				scrollPosition = $( '#chatMessages' ).scrollTop() == ( $( '#chatMessages' )[0].scrollHeight-$( '#chatMessages' ).height() ); // Gives true, if at the bottom.
				
				$.each( data.posts, function( i, row ) {
					var chat_id = parseInt( row.chat_id );
					var user_id = parseInt( row.user_id );
					var user_name = row.user_name;
					var date = row.date;
					var message = row.message;
					if ( typeof $( '#chat_row_'+chat_id) != 'undefined' && $( '#chat_row_'+chat_id).length==0 )
						$( '<div/>' )
							.attr( 'id', 'chat_row_'+chat_id )
							.html( '<span class="chatNick">'+user_name+'</span>: '+message )
							.appendTo( 'div#chatMessages' );
				});
				
				if ( scrollPosition || scrollFirst )
					$( '#chatMessages' ).scrollTop( $( '#chatMessages' )[0].scrollHeight );
				
				scrollFirst = false;
			}
			,error: function( XMLHttpRequest, textStatus, errorThrown ) {
				list_chat();
			}
		});
		
	}

/* Check whether there is a new entry. */
	var last_chat;
	function get_last_chat() {
		
		$.ajax({
			url: 'get_last_chat.php'
			,timeout: 10000
			,success: function( data ){
				if ( data!=0 ) {
					data = parseInt( data );
					if ( typeof last_chat == 'undefined' || last_chat < data ) {
						last_chat = data;
						list_chat();
					}
				}
				setTimeout( function(){
					get_last_chat();
				}, 500 );
			}
			,error: function( XMLHttpRequest, textStatus, errorThrown ) {
				get_last_chat();
			}
		});
		
	}

/* Post something to chat. */
	function post_to_chat( message, private ) {
		
		if ( private==null )
			private='';
		$.ajax({
			url: 'post_to_chat.php'
			,timeout: 10000
			,type: 'POST'
			,data: {
				'message': message
				,'private': private
			}
			,success: function( data ) {
				get_last_chat();
			}
			,error: function( XMLHttpRequest, textStatus, errorThrown ) {
				$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
					'message': c['error_default_session_error']
				});
			}
		});
		
	}
	
/* Throw some dices. */
	function dice() {
		
		var d = $( '#diceForm input[name="d"]' ).val();
		var db = $( '#diceForm input[name="db"]' ).val();
		
		if ( d == '' )
			d = 6;
		if ( db == '' )
			db = 1;
			
		var res = Math.floor( Math.random()*d )+1;
		
		for (var i=1; i<db; i++)
			res += ', '+( Math.floor( Math.random()*d )+1 );
		
		var message = 'Dobás(d'+d+', '+db+' darab): '+res;
		post_to_chat( message );
		
	}

	/*
	// Not used anywhere, deprecated?
	function map_side_update() {
		var new_id = $('#allWrapper div.map_side:last-child').attr('id');
		new_id = parseInt(new_id.substr(11));
		var new_map = $('<div/>').attr('id', new_id).addClass('map_side').html('').click(function(){
			
		});
	}
	
	*/
/* Showing new event window. */
	function add_new_event_toggle() {
		list_selectable_privates();
		if( $( '#add_new_entry_wrapper' ).css( 'display' )=='none' )
			$( '#add_new_entry_wrapper' ).fadeIn( 200 );
		else
			$( '#add_new_entry_wrapper' ).fadeOut( 200 );
	}
	
/* Checking iframe map upload. */
	var ready_map;
	$.cookie( 'ready_map', null );
	$.cookie( 'ready_map', 0, { path: '/' } );
	function map_uploaded_check(){
		
		ready_map = $.cookie( 'ready_map' );
		if ( ready == '1' ) {
			list_maps( true );
			$.cookie( 'ready_map', 0, { path: '/' } );
		} else
			setTimeout(function(){
				map_uploaded_check();
			}, 500 );
			
	}
	
/* Show a specific entry. */
	var showing_entry = -1;
	function show_entry( id, label, description ) {
		
		if ( act_user_is_gm )
			list_selectable_privates( id );
		
		showing_entry = id;
		$( 'div#entrypics_cont' ).empty();
		list_entry_pics( id );
		$( '#entries_upload input[name=entry_id]' ).val( id );
		$( '#show_entry_wrapper div.title' ).html( label );
		$( '#show_entry_wrapper div.desc' ).html( description );
		$( '#show_entry_wrapper' ).fadeIn( 200 );
		
	}
	
/* Confirm: Delete a specific picture from an entry. */
	function del_entrypic( entry_id, pic_id ) {
		
		forward_func = del_entrypic_confirmed.bind( null, entry_id, pic_id );
		$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
			'message': c['sure_to_delete']
			,'ok_btn': forward_func
		});
		
	}

/* Confirmed: Delete a specific picture from an entry. */
	function del_entrypic_confirmed( entry_id, pic_id ) {
		
		$( '#show_entry_wrapper' ).hv_ajax_loader( 'toggle_loading', 'on' );
		var tumbsrc = 'game_folders/'+game_id+'/'+entry_id+'/tumb_'+pic_id+'.jpg';
		var src = 'game_folders/'+game_id+'/'+entry_id+'/'+pic_id+'.jpg';
		$.ajax({
			url: 'del_entry_pic.php'
			,type: 'POST'
			,data: {
				'src': src
				,'tumbsrc': tumbsrc
			}
			,success: function( data ){
				if ( data=='0' ) {
					$( '#'+pic_id ).remove();
					list_entry_pics( entry_id );
				} else
					$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
						'message': c['error_default_ajax_error']+' '+data
					});
			}
			,error: function( jqXHR, textStatus, errorThrown ){
				$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
					'message': c['error_default_ajax_error']
				});
			}
			,complete: function( data ){
				$( '#show_entry_wrapper' ).hv_ajax_loader( 'toggle_loading', 'off' );
			}
		});
		
	}
	
/* List pictures attached to a specific entry. */
	function list_entry_pics( entry_id ) {
		
		$( '#entrypics_wrap' ).hv_ajax_loader( 'toggle_loading', 'on' );
		$.ajax({
			url: 'list_entry_pics.php'
			,type: 'POST'
			,data: {
				'entry_id': entry_id
			}
			,dataType: 'json'
			,success: function(data){
				if ( data!=0 ) {
					$( 'div#entrypics_cont .entrypic' ).addClass( 'entrypic_to_remove' );
					$.each( data.posts, function( i, pic ) {
						var id = parseInt( pic.id );
						if ( typeof $( 'div#'+id ) != 'undefined' && $( 'div#'+id ).length==0 ) {
							var tumb_url = 'game_folders/'+game_id+'/'+entry_id+'/tumb_'+id+'.jpg';
							var url = 'game_folders/'+game_id+'/'+entry_id+'/'+id+'.jpg';
							$( '<div/>' )
								.addClass( 'entrypic' )
								.attr( 'id', id )
								.prependTo( 'div#entrypics_cont' );
							$( '<img/>' )
								.addClass( 'entrypic_img' )
								.attr( 'src', tumb_url )
								.prependTo( 'div#'+id )
								.click( function(){
									window.open( url );
								} );
							if ( act_user_is_gm ) {
								$( '<div/>' )
									.addClass( 'del_entrypic' )
									.prependTo( 'div#'+id )
									.click( function(){
										del_entrypic( entry_id, id );
									});
							}
						} else
							$( 'div#'+id ).removeClass( 'entrypic_to_remove' );
					});
					$( 'div#entrypics_cont .entrypic_to_remove' ).remove();
				}
				var entry_width = 0;
				$( 'div#entrypics_cont .entrypic' ).each( function(){
					entry_width += $( this ).outerWidth()+10;
				});
				$( 'div#entrypics_cont' ).width( entry_width );
			},
			complete: function() {
				$( '#entrypics_wrap' ).hv_ajax_loader( 'toggle_loading', 'off' );
			}
		});
	}

/* List entries. */
/* !!!Way too redundant, needs to be updated!!! */
	function list_entries( entry_list ) {
		
		if ( entry_list==null ) {
			$.ajax({
				url: 'list_entries.php'
				,timeout: 10000
				,dataType: 'json'
				,success: function( data ){
					$( '#allWrapper div.entry_box' ).addClass( 'entries_to_remove' );
					$.each( data.posts, function( i ) {
						var id = parseInt( data.posts[i].eventID );
						var label = data.posts[i].label;
						var description = data.posts[i].description;
						var last_updated = data.posts[i].last_updated;
						
						//Update check
						if ( typeof $( '#entry_to_del_'+id ) != 'undefined' && $( '#entry_to_del_'+id ).length!=0) {
							if ( $( '#entry_to_del_'+id ).lu!=last_updated )
								$( '#entry_to_del_'+id ).remove();
						}
						
						if ( typeof $( '#entry_to_del_'+id) != 'undefined' && $( '#entry_to_del_'+id).length==0 ) {
							$( '<div/>' )
								.addClass( 'entry_box' )
								.css( 'display', 'none' )
								.attr( 'id', 'entry_to_del_'+id )
								.appendTo( '#allWrapper div.entryWrapper' );
							$( '<div/>' )
								.addClass( 'disable' )
								.appendTo( '#entry_to_del_'+id );
							$( '<div/>' )
								.addClass( 'entry' )
								.html( label )
								.appendTo( '#entry_to_del_'+id );
								
							$( '#entry_to_del_'+id ).lu = last_updated;
							
							if ( act_user_is_gm )
								$( '<div/>' )
									.addClass( 'x' )
									.html( 'X' )
									.appendTo( '#entry_to_del_'+id );
							$( '#entry_to_del_'+id+' div.entry' ).click( function(){
								show_entry( id, label, description );
							} );
							if ( act_user_is_gm ) {
								$( '#entry_to_del_'+id+' div.x' ).click( function(){
									
									forward_func = entry_delete_confirmed.bind( null, id );
									$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
										'message': c['sure_to_delete']
										,'ok_btn': forward_func
									});
									
									function entry_delete_confirmed( id ){
										$( '#entry_to_del_'+id+' div.disable' ).css( 'display', 'block' );
										$.ajax({
											type: 'POST'
											,url: 'delete_entry.php'
											,data: {
												'eventID': id
											}
											,success: function( data ){
												if ( data=='0' ) {
													$( '#entry_to_del_'+id ).fadeOut( 200 );
													if ( showing_entry==id )
														$( '#show_entry_wrapper' ).fadeOut( 200 );
												} else
													$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
														'message': c['error_delete_entry']+' '+data
													});
											},
											error: function( jqXHR, textStatus, errorThrown ){
												$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
													'message': c['error_default_ajax_error']
												});
											},
											complete: function( data ){
												$( '#entry_to_del_'+id+' div.disable' ).css( 'display', 'none' );
											}
										});
									}
									
								});
							}
							$( '#entry_to_del_'+id ).fadeIn( 200 );
						} else
							$( '#entry_to_del_'+id ).removeClass( 'entries_to_remove' );
					});
					$( '#allWrapper div.entries_to_remove' ).fadeOut( 200 );
				}
			});
		} else {
			$( '#allWrapper div.entry_box' ).addClass( 'entries_to_remove' );
			$.each( entry_list, function( i, list ) {
				var id = parseInt( list.eventID );
				var label = list.label;
				var description = list.description;
				var last_updated = list.last_updated;
				
				//Update check
				if ( typeof $( '#entry_to_del_'+id ) != 'undefined' && $( '#entry_to_del_'+id ).length!=0 ) {
					if ( $( '#entry_to_del_'+id ).lu!=last_updated )
						$( '#entry_to_del_'+id ).remove();
				}
				
				if ( typeof $( '#entry_to_del_'+id ) != 'undefined' && $( '#entry_to_del_'+id ).length==0 ) {
					$( '<div/>' )
						.addClass( 'entry_box' )
						.css( 'display', 'none' )
						.attr( 'id', 'entry_to_del_'+id )
						.appendTo( '#allWrapper div.entryWrapper' );
					$( '<div/>' )
						.addClass( 'disable' )
						.appendTo( '#entry_to_del_'+id );
					$( '<div/>' )
						.addClass( 'entry' )
						.html( label )
						.appendTo( '#entry_to_del_'+id );
					$( '#entry_to_del_'+id ).lu = last_updated;
					
					if ( act_user_is_gm )
						$( '<div/>' )
							.addClass( 'x' )
							.html( 'X' )
							.appendTo( '#entry_to_del_'+id );
					$( '#entry_to_del_'+id+' div.entry' ).click( function(){
						show_entry( id, label, description );
					});
					if ( act_user_is_gm ) {
							
						$( '#entry_to_del_'+id+' div.x' ).click( function(){
							
							forward_func = entry_delete_confirmed.bind( null, id );
							$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
								'message': c['sure_to_delete']
								,'ok_btn': forward_func
							});
							
							function entry_delete_confirmed( id ){
								$( '#entry_to_del_'+id+' div.disable' ).css( 'display', 'block' );
								$.ajax({
									type: 'POST'
									,url: 'delete_entry.php'
									,data: {
										'eventID': id
									}
									,success: function( data ){
										if ( data=='0' ) {
											$( '#entry_to_del_'+id ).fadeOut( 200 );
											if ( showing_entry==id )
												$( '#show_entry_wrapper' ).fadeOut( 200 );
										} else
											$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
												'message': c['error_delete_entry']+' '+data
											});
									}
									,error: function( jqXHR, textStatus, errorThrown ){
										$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
											'message': c['error_default_ajax_error']
										});
									}
									,complete: function( data ){
										$( '#entry_to_del_'+id+' div.disable' ).css( 'display', 'none' );
									}
								});
							}
							
						});
					}
					$( '#entry_to_del_'+id ).fadeIn( 200 );
				} else
					$( '#entry_to_del_'+id ).removeClass( 'entries_to_remove' );
			});
			$( '#allWrapper div.entries_to_remove' ).fadeOut( 200 );
		}
		
	}

/* "Click" on new map form. */
	function add_new_map() {
		
		$( '#upload_map input[type=file]' ).val( '' );
		$( '#upload_map input[type=file]' ).click();
		return false;
		
	}

/* List maps. */
/* !!!Way too redundant, needs to be updated!!! */
	function list_maps( map_list, set_src ) {
		
		var set_src = ( set_src==null ) ? false : true;
		var first = true;
		if ( map_list==null ) {
			$.ajax({
				url: 'list_maps.php'
				,timeout: 10000
				,dataType: 'json'
				,success: function( data ){
					$( '#allWrapper div.map_side' ).addClass( 'maps_to_remove' );
					if ( data!='0' ) {
						$.each( data.posts, function( i ) {
							var id = data.posts[i].id;
							var name = data.posts[i].name;
							var src = data.posts[i].src;
							var tumbsrc = data.posts[i].tumbsrc;
							if ( first==true && set_src==true ) {
								change_map_src( src );
								first = false;
							}
							if ( typeof $( '#map_to_del_'+id ) != 'undefined' && $( '#map_to_del_'+id ).length==0 ) {
								$( '<div/>' )
									.addClass( 'map_side' )
									.css( 'display', 'none' )
									.attr( 'id', 'map_to_del_'+id )
									.appendTo( '#allWrapper div.mapWrapper' );
								$( '<div/>' )
									.addClass( 'disable' )
									.appendTo( '#map_to_del_'+id );
								$( '<div/>' )
									.addClass( 'map_name' )
									.css( 'background-image', 'url('+tumbsrc+')' )
									.html( name )
									.appendTo( '#map_to_del_'+id );
								if ( act_user_is_gm )
									$( '<div/>' )
										.addClass( 'x' )
										.html( 'X' )
										.appendTo( '#map_to_del_'+id );
								$( '#map_to_del_'+id+' div.map_name' ).click( function(){
									change_map_src( src );
								} );
								if ( act_user_is_gm ) {
									$( '#map_to_del_'+id+' div.x' ).click( function(){
										
										forward_func = forward_confirmed.bind( null, id, name, src );
										$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
											'message': c['sure_to_delete']
											,'ok_btn': forward_func
										});
										
										function forward_confirmed( id, name, src ) {
											$( '#map_to_del_'+id+' div.disable' ).css( 'display', 'block' );
											$.ajax({
												type: 'POST'
												,url: 'delete_map.php'
												,data: {
													'name': name
												}
												,success: function( data ){
													if ( data=='0' && $( '#mapimg img' ).attr( 'src' )==src )
														list_maps( true );
													else if ( data!='0' )
														$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
															'message': c['error_delete_map']+' '+data
														});
												},
												error: function( jqXHR, textStatus, errorThrown ){
													$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
														'message': c['error_default_ajax_error']
													});
												},
												complete: function(){
													$( '#map_to_del_'+id+' div.disable' ).css( 'display', 'none' );
												}
											});
										}
										
									});
								}
								$( '#map_to_del_'+id ).fadeIn( 200 );
							} else
								$( '#map_to_del_'+id ).removeClass( 'maps_to_remove' );
						});
					} else
						change_map_src( 'default' );
					
					$( '#allWrapper div.maps_to_remove' ).fadeOut( 200 );
				}
			});
		} else {
			$( '#allWrapper div.map_side' ).addClass( 'maps_to_remove' );
			$.each( map_list, function( i, list ) {
				var id = list.id;
				var name = list.name;
				var src = list.src;
				var tumbsrc = list.tumbsrc;
				if ( first==true && set_src==true ) {
					change_map_src( src );
					first = false;
				}
				if ( typeof $( '#map_to_del_'+id ) != 'undefined' && $( '#map_to_del_'+id ).length==0 ) {
					$( '<div/>' )
						.addClass( 'map_side' )
						.css( 'display', 'none' )
						.attr( 'id', 'map_to_del_'+id )
						.appendTo( '#allWrapper div.mapWrapper' );
					$( '<div/>' )
						.addClass( 'disable' )
						.appendTo( '#map_to_del_'+id );
					$( '<div/>' )
						.addClass( 'map_name' )
						.css( 'background-image', 'url('+tumbsrc+')' )
						.html( name )
						.appendTo( '#map_to_del_'+id );
						
					if ( act_user_is_gm )
						$( '<div/>' )
							.addClass( 'x' )
							.html( 'X' )
							.appendTo( '#map_to_del_'+id );
							
					$( '#map_to_del_'+id+' div.map_name' ).click( function(){
						change_map_src( src );
					} );
					if ( act_user_is_gm ) {
						$( '#map_to_del_'+id+' div.x' ).click( function(){
							
							forward_func = forward_confirmed.bind( null, id, name, src );
							$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
								'message': c['sure_to_delete']
								,'ok_btn': forward_func
							});
							
							function forward_confirmed( id, name, src ) {
								$( '#map_to_del_'+id+' div.disable' ).css( 'display', 'block' );
								$.ajax({
									type: 'POST'
									,url: 'delete_map.php'
									,data: {
										'name': name
									}
									,success: function( data ){
										if ( data=='0' ) {
											if ( $( '#mapimg img' ).attr( 'src' )==src )
												list_maps( null, true );
											else
												list_maps( null, false );
										} else if ( data!='0' )
											$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
												'message': c['error_delete_map']+' '+data
											});
									}
									,error: function( jqXHR, textStatus, errorThrown ){
										$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
											'message': c['error_default_ajax_error']
										});
									}
									,complete: function( data ){
										$( '#map_to_del_'+id+' div.disable' ).css( 'display', 'none' );
									}
								});
							}
						});
					}
					$( '#map_to_del_'+id ).fadeIn( 200 );
				} else
					$( '#map_to_del_'+id ).removeClass( 'maps_to_remove' );
			});
			$( '#allWrapper div.maps_to_remove' ).fadeOut( 200 );
		}
		
	}

/* Waiting for everything to be loaded. */
	var loaded = false;
	function loading_screen() {
		
		if ( loaded == true )
			$( ' #loading_screen div.title' ).fadeOut( 100, function(){
				$( '#loading_screen' ).fadeOut( 500 );
			} );
		else {
			setTimeout( function(){
				loading_screen();
			}, 250 );
		}
		
	}

/* Checking iframe upload for entries. */
	var ready;
	$.cookie( 'ready', null );
	$.cookie( 'ready', 0, { path: '/' } );
	function entries_uploaded_check( id ){
		
		ready = $.cookie( 'ready' );
		if ( ready == '1' ) {
			$( 'div#show_entry_wrapper .disable' ).css( 'display', 'none' );
			$.cookie( 'ready', 0, { path: '/' } );
			list_entry_pics( id );
		} else
			setTimeout(function(){
				entries_uploaded_check( id );
			}, 100);
			
	}

/* List the privacy settings for an entry. */
	var first_selectable = true;
	function list_selectable_privates( id ){
		
		var idname;
		if ( id==null ) {
			id=-1;
			idname='add_new';
		} else
			idname='show';
			
		$.ajax({
			url: 'list_selectable_privates.php'
			,type: 'POST'
			,data: {
				'entry_id': id
			}
			,success: function( data ){
				$( '#'+idname+'_entry_wrapper' ).find( '.selectable_list_cont' ).html( data );
				if ( id!=-1 ) {
					$( '#show_entry_wrapper' ).find( 'input[name=select_type]' ).attr( 'checked', false );
					$( '#show_entry_wrapper' ).find( 'input[value='+$('span#privacy_group' ).html()+']' ).attr( 'checked', true );
				}
				var width = 0;
				$( '#'+idname+'_entry_wrapper' )
					.find( '.selectable_list_cont' )
					.children()
					.click(function(){
						$( '#othersel_show' ).attr( 'checked', 'checked' );
					});
				$( '#'+idname+'_entry_wrapper' )
					.find( '.selectable_list_cont' )
					.children( 'input, div' )
					.each( function(){
						width += $( this ).outerWidth( true );
					});
				if ( width!=0 )
					$( '#'+idname+'_entry_wrapper' )
						.find( '.selectable_list_cont' )
						.width( width );
			}
		});
		
	}

/* Update the current entry showing. */
	function update_entry() {
		
		forward_func = forward_func_confirmed.bind( null );
		$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
			'message': c['sure_to_update']
			,'ok_btn': forward_func
		});
		
		function forward_func_confirmed() {
			$( '#entrypics_wrap' ).hv_ajax_loader( 'toggle_loading', 'on' );
			var allow_str = '';
			$( 'div#show_entry_wrapper' )
				.find( 'input.allowed:checked' )
				.each(function(){
					allow_str = allow_str+$(this).val()+';';
				});
			if ( allow_str=='' )
				allow_str=';';
			$.ajax({
				url: 'update_entry.php'
				,type: 'POST'
				,data: {
					'entry_id': showing_entry
					,'label': $( 'div#show_entry_wrapper div.title' ).html()
					,'description': $( 'div#show_entry_wrapper div.desc' ).html()
					,'select_type': $( 'div#show_entry_wrapper' ).find( 'input[name=select_type]:checked' ).val()
					,'allowed': allow_str
				}
				,success: function( data ){
					if ( data!='0' )
						$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
							'message': c['error_entry_modify_fail']
						});
				}
				,error: function(){
					$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
						'message': c['error_default_ajax_error']
					});
				}
				,complete: function(){
					$( '#entrypics_wrap' ).hv_ajax_loader( 'toggle_loading', 'off' );
				}
			});
		}
		
	}

/* Events side container resizing. */
	var entries_all_height;
	var mapheight;
	var mapresizeheight;
	var entryheight;
	function reset_events_height( manual_resizing ) {
		if ( !manual_resizing ) {
			
			if ( ($( window ).height()-120)<=40 )
				return;
				
			if ( entries_all_height==null ) {
				entries_all_height = $( window ).height()-120;
				mapheight = Math.ceil( entries_all_height/2 );
				entryheight = entries_all_height-mapheight;
				$( '#eventsWrapper div.mapWrapper' ).height( mapheight );
				$( '#eventsWrapper div.entryWrapper' ).height( entryheight );
			} else {
				var distance = entries_all_height - ($( window ).height()-120);
				if ( mapheight<=20 && distance>-1 ) {
					entryheight = $( '#eventsWrapper div.entryWrapper' ).height() - distance;
					$( '#eventsWrapper div.entryWrapper' ).height( entryheight );
				} else if ( entryheight<=20 && distance>-1 ) {
					mapheight = $( '#eventsWrapper div.mapWrapper' ).height() - distance;
					$( '#eventsWrapper div.mapWrapper' ).height( mapheight );
				} else {
					map_distance = Math.ceil( distance/2 );
					entry_distance = distance-map_distance;
					mapheight = $( '#eventsWrapper div.mapWrapper' ).height() - map_distance;
					$( '#eventsWrapper div.mapWrapper' ).height( mapheight );
					entryheight = $( '#eventsWrapper div.entryWrapper' ).height() - entry_distance;
					$( '#eventsWrapper div.entryWrapper' ).height( entryheight );
				}
				entries_all_height = $( window ).height()-120;
			}
			$( '#eventsWrapper div.mapWrapper' ).resizable( 'option', 'maxHeight', ($( window ).height()-120)-20 );
		} else {
			entryheight = $( '#eventsWrapper div.entryWrapper' ).height() + mapresizeheight-$( '#eventsWrapper div.mapWrapper' ).height();
			$( '#eventsWrapper div.entryWrapper' ).height( entryheight );
			mapresizeheight = $( '#eventsWrapper div.mapWrapper' ).height();
		}
	}
	
	
/* If everything is loaded. */
	$( window ).load(function( e ) {
		
		loaded = true;
		
	});
	
/* On window resizing. */
	$( window ).resize(function( e ) {
		
		reset_events_height();
		
	});
	
/* If the DOM's fully loaded. */
	$(function() {
		
		/*
		FEJLESZTENI AZ ÖTLETET
		drag-re az aktuális ablak legyen legfelül.
		Ez önmagában nem elég, az ablakok legutóbbi kattintás sorrendje is fontos még.
		
		var dragger_obj_now;
		var dragger_z_now;
		$('.dragger').mousedown(function(){
			if (dragger_obj_now != null) {
				dragger_obj_now.css('z-index', dragger_z_now);
			}
			dragger_obj_now = $(this).parent();
			dragger_z_now = $(this).css('z-index');
			$(this).parent().css('z-index', 11000);
		});
		*/
		
		/* Entry upload button. */
			$( '#entry_pic_up_button' ).click(function(){
				$( '#entries_upload input[type=file]' ).val( '' );
				$( '#entries_upload input[type=file]' ).click();
			});
		
		/* Start entry iframe file uploading on input change. */
			$( '#entries_upload input[type=file]' ).change(function() {
				var entry_id = $( '#entries_upload input[name=entry_id]' ).val();
				$( '#entrypics_wrap' ).hv_ajax_loader( 'toggle_loading' );
				$( '#entries_upload' ).submit();
				setTimeout(function(){
					entries_uploaded_check( entry_id );
				}, 100);
			});
		
		/* Call entry update on current shown entry. */
			$( '#show_entry_wrapper div.updating' ).click(function(){
				update_entry();
			});
		
		/* Exit from new entry window. */
			$( '#add_new_entry_wrapper div.exit' ).click(function(){
				$( '#add_new_entry_wrapper' ).fadeOut( 200 );
			});
			
		/* Exit from opened entry window. */
			$( '#show_entry_wrapper div.exit' ).click(function(){
				showing_entry = -1;
				$( '#show_entry_wrapper' ).fadeOut( 200 );
			});
		
		/* Start entry uploading. */
			$( '#add_new_entry_wrapper div.submit' ).click(function(){
				$( '#show_entry_wrapper' ).hv_ajax_loader( 'toggle_loading' );
				$.ajax({
					type: 'POST'
					,url: 'upload_entry.php'
					,data: $( '#add_new_entry_form' ).serialize()
					,success: function( data ) {
						if ( parseInt( data )>-1 ) {
							$( '#add_new_entry_form' )[0].reset();
							sync_data();
						}
						else
							$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
								'message': c['error_upload_entry']+' '+data
							});
					}
					,complete: function(){
						$( '#show_entry_wrapper' ).hv_ajax_loader( 'toggle_loading' );
					}
				});
			});
		
		/* Start map uploading on input change. */
			$( '#upload_map input[type=file]' ).change(function() {
				$( '#upload_map' ).submit();
			});
		
		/* Exit from character sheet window. */
			$( '#character_sheet div.x' ).click(function(){
				$( '#character_sheet' ).fadeOut( 100 );
			});
			
		/* Change default submit for chat post. */
			$( '#chatForm' ).submit(function( e ){
				e.preventDefault();
				post_to_chat( $( '#chat_message' ).val() );
				$( '#chat_message' ).val( '' );
			});
			
		/* Setting up basic dimensions for character sheet. */
		/* Not working very well yet... Better than nothing though. */
			var margin_left_cs = (-1)*($( '#character_sheet' ).width()/2);
			var margin_top_cs = (-1)*($( '#character_sheet' ).height()/2);
			$( '#character_sheet' ).css({
				'margin-left': margin_left_cs
				,'margin-top': margin_top_cs
			});
			
		/* Change to manual syncing. */
			$( '#ping_wrapper div.manual' ).click(function(){
				$( this ).toggleClass( 'off_now' );
				if ( sync_to_manual ) {
					sync_data();
					$( '#ping_wrapper div.manual_sync_now' ).animate({'width': '0px'}, 100);
					$( '#ping_wrapper' ).animate({
						'width': '100px'
						,'margin-left': '-50px'
					}, 100);
				} else {
					clearTimeout( sync_timer );
					$( '#ping_wrapper div.manual_sync_now' ).animate({'width': '40px'}, 100);
					$( '#ping_wrapper' ).animate({
						'width': '150px'
						,'margin-left': '-75px'
					}, 100);
				}
				
				sync_to_manual = !sync_to_manual;
			});
		
		/* Sync manually on click. */
			$( '#ping_wrapper div.manual_sync_now' ).click(function(){
				sync_data();
			});
		
		
		/* Setting up HV Ajax Loaders */
		
			$( '#charsheet_wrapper' ).hv_ajax_loader({
				'cover_on': true
				,'cover_attach_class': 'charsheet_rounding'
			});
			
			$( '#add_new_entry_wrapper' ).hv_ajax_loader({
				'cover_on': true
				,'cover_attach_class': 'charsheet_rounding5'
			});
			
			$( '#map div.container, #entrypics_wrap, #show_entry_wrapper, #entry_wrapper' )
				.hv_ajax_loader({
					'cover_on': true
				});
		
		
		/* Setting up draggable elements. */
		
			$( '#add_new_entry_wrapper' ).draggable({
				handle: '.dragger'
			}).resizable({
				minWidth: 300
				,minHeight: 250
				,alsoResize: 'input[name=title], textarea[name=desc]'
			});
			
			$( '#show_entry_wrapper' ).draggable({
				handle: '.dragger'
			}).resizable({
				minWidth: 300
				,minHeight: 250
				,alsoResize: '#show_entry_wrapper div.desc'
			});
			
			$( '#character_sheet, #desc_container, #allWrapper .diceWrapper, #allWrapper div.chatWrapper, #gamersWrapper, #ping_wrapper, #friends_to_invite, #info' )
				.draggable({
					handle: '.dragger'
				});
			
			
		/* Setting up resizable elements. */
		
			if ( !charsheet_noresize )
				$( '#character_sheet' ).resizable({
					handles: 'all'
					,minWidth: resize_min_width
					,minHeight: resize_min_height
					,maxWidth: resize_max_width
					,maxHeight: resize_max_height
				});
				
			$( '#desc_container' ).resizable({
				handles: 'all'
				,minWidth: 200
				,minHeight: 200
			});
			
			$( '#mapimg' )
				.draggable()
				.resizable({
					handles: 'all'
					,resize: function( event, ui ) {
						$( '#mapimg img' ).width( $( '#mapimg' ).width()-2 );
						$( '#mapimg img' ).height( $( '#mapimg' ).height()-2 );
					}
				});
				
			$( '#allWrapper div.chatWrapper' ).resizable({
				minWidth: 250
				,minHeight: 80
				,alsoResize: '.also_resize'
				,resize: function(){
					$( '#chatMessages' ).css( 'max-height', ( $( '#chatBox' ).height()) );
				}
			});
			
			$( '#eventsWrapper div.mapWrapper' ).resizable({
				handles: 's'
				,minHeight: 20
				,start: function(){
					mapresizeheight = $( this ).height();
				}
				,resize: function(){
					 reset_events_height( true );
				}
			});
			
			$( '#gamersWrapper' ).resizable({
				grid: [200, 30]
				,handles: 's'
				,minHeight: 60
			});
			
			$( '#allWrapper div.eventsWrapper' ).resizable({
				handles: 'w'
				,minWidth: 200
				,maxWidth: 500
			});
			
			$( '#hv_alert_wrapper' ).draggable({
				handle: '.title'
			}).resizable({
				handles: 's,e,se'
				,minWidth: 115
				,minHeight: 180
				,alsoResize: '#hv_alert_wrapper div.content'
			});
			
			
		/* Setting up the HV Alert box. */
		
			$( '#hv_alert_wrapper' ).hv_alert();
			
		
		/* Start background functions. */
		
			reset_events_height();
			loading_screen();
			get_last_chat();
			
			if (!sync) {
				get_last_entry();
				list_maps( true );
				get_last_map();
				update_in_game_user();
				check_in_game_users();
			} else
				sync_data();
		
	});