

/* Checking for new messages. */
	var new_message_timer;
	function new_message() {
		$.ajax({
			url: PAGE_ROOT+'call/new_message.php'
			,dataType: 'json'
			,timeout: 10000
			,success: function( data ) {
				var new_request = false;
				$.each( data, function( requests ) {
					$.each( data.requests, function( i ) {
						var id = ( ( data.requests[i].gameRequestID != null ) ? 'g' : 'f' )+'_request_'+data.requests[i].gameRequestID;
						if ( typeof $( '#'+id ) != 'undefined' && $( '#'+id ).length == 0 ) {
							new_request = true;
							var html = ( data.requests[i].gameRequestID != null ) ? (c['request_title_game']+'('+data.requests[i].gameRequestID+')') : c['request_title_friend'];
							var div = $( '<div/>' ).addClass( 'request' ).attr( 'id', id ).html( html );
							div.appendTo( '#newMessage .content' );
							$( '<div/>' ).addClass( 'accept' ).html( 'A' ).appendTo( '#'+id );
							$( '<div/>' ).addClass( 'ignore' ).html( 'X' ).appendTo( '#'+id );
							
							/* Ignore incoming gamerequest */
							$( '#'+id+' div.ignore' ).click(function(){
								$.ajax({
									url: PAGE_ROOT+'call/delete_request.php'
									,type: 'POST'
									,data: {
										'id': data.requests[i].spec_id
									}
									,success: function( data ) {
										if ( data == '1' )
											$( '#'+id ).fadeOut( 200, function(){
												$('#'+id).remove();
											});
										else
											$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
												'message': c['error_ignore_friend_request']
											});
									}
								});
							});
							
							if ( data.requests[i].gameRequestID != null) {
								/* Accepting game request. */
									$( '#'+id+' div.accept' ).click( function(){
										var forward_url = PAGE_ROOT+'game/?id='+data.requests[i].gameRequestID;
										$.ajax({
											url: PAGE_ROOT+'call/registrate_in_game.php',
											type: 'POST',
											data: {
												'game_id': data.requests[i].gameRequestID
											},
											success: function( data ) {
												if ( data == '0' )
													window.location = forward_url;
												else
													$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
														'message': c['error_accept_game_request']
													});
											}
										});
									});
							} else {
								/* Accepting friend request. */
									$('#'+id+' div.accept').click( function(){
										$.ajax({
											url: PAGE_ROOT+'call/accept_friend_request.php',
											type: 'POST',
											data: {
												'user_id': data.requests[i].friendRequestID
												,'spec_id': data.requests[i].spec_id
											},
											success: function( data ) {
												if ( data == '1' )
													window.location.reload();
												else
													$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
														'message': c['error_accept_friend_request']+data
													});
											}
										});
									});
							}
						}
					});
				});
				if ( new_request )
					$( '#newMessage div.label' ).addClass( 'active' );
				
			},
			complete: function(){
				new_message_timer = setTimeout(function(){
					new_message();
				}, 1000);
			}
		});
	}
	
/* If the DOM's fully loaded. */
	var requests_opened = false;
	var langs_opened = false;
	$(function(){
		
		/* Toggle incoming messages box. */
			$( '#newMessage div.label' ).click( function() {
				if ( requests_opened )
					$( '#newMessage' ).animate( {height: '20px'}, 50 );
				else {
					$( '#newMessage' ).animate( {height: '100px'}, 50 );
					$( '#newMessage div.label' ).removeClass( 'active' );
				}
				
				requests_opened = !requests_opened;
			});
			
		/* Toggle language chooser. */
			$( '#langs div.label' ).click(function() {
				if ( langs_opened )
					$( '#langs' ).stop().animate({top: '-52px'}, 50);
				else
					$( '#langs' ).stop().animate({top: '-1px'}, 50);
					
				langs_opened = !langs_opened;
			});
			
		/* Emphasize flags for language chooser. */
			$( '#langs img' ).mouseover(function() {
				$( this ).css({'margin': '0px', 'height': '40px', 'width': '58px'}, 100);
			}).mouseout(function() {
				$( this ).css({'margin': '5px', 'height': '30px', 'width': '48px'}, 100);
			});
			
		/* Start message polling. */
			new_message();
			
		/* Setup HVAlert */
			$( '#hv_alert_wrapper' ).hv_alert();
		
	});