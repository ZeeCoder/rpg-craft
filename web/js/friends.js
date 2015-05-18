
/* Send friend request */
	function send_friend_request( user_id ) {
		$.ajax({
			url: PAGE_ROOT+'call/send_friend_request.php'
			,data: {
				'user_id': user_id
			}
			,timeout: 2000
			,success: function( data ){
				var msg;
				if ( data == '1' )
					msg = c['fr_successful'];
				else if ( data == '2' )
					msg = c['fr_already_sent'];
				else if ( data == '3' )
					msg = c['fr_already_friend'];
				else
					msg = c['error_send_friend_request']+' '+data;
				
				$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
					'message': msg
				});
			}
			,error: function() {
				$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
					'message': c['error_send_friend_request']
				});
			}
		});
	}
	
	var searchbox_opened = false;
	
/* Executing search. */
	function submitFriendSearch() {
		$( '#friendSearchForm' ).hv_ajax_loader( 'toggle_loading', 'on' );
		$.ajax({
			type: 'POST'
			,url: PAGE_ROOT+'call/friends_search.php'
			,data: $( '#friendSearchForm' ).serialize()
			,success: function( data ){
				if ( !searchbox_opened ) {
					$( '#friendsContainer div.close_search' ).fadeIn( 200 );
					$( '#searchResults' ).html( data ).height( 0 ).animate( {height: '240'}, 200 );
					searchbox_opened=!searchbox_opened;
				} else {
					$( '#searchResults' ).animate( {opacity: 0}, 200 );
					setTimeout( function() {
						$( '#searchResults' ).html( data ).animate( {opacity: 1}, 200 );
					}, 200);
				}
			}
			,error: function(){
				$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
					'message': c['error_default_ajax_error']+' '+data
				});
			}
			,complete: function(){
				$( '#friendSearchForm' ).hv_ajax_loader( 'toggle_loading', 'off' );		}
		});
		$( '#keyWord' ).val( '' );
	}
	
/* Confirm deleting friend. */
	function delete_friend( user_id ) {
		f_delete_friend = delete_friend_confirmed.bind( null, user_id );
		$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
			'message': c['confirm_friend_delete']
			,'ok_btn': f_delete_friend
		});
	}
	
/* Deleting friend. */
	function delete_friend_confirmed( user_id ) {
		$.ajax({
			url: PAGE_ROOT+'call/delete_friend_request.php',
			data: 'user_id='+user_id,
			timeout: 2000,
			success: function(data){
				if (data == '1') $('#friend_del_'+user_id).fadeOut(200);
				else alert(c['error_ignore_friend_request']+' '+data);
			},
			error: function() {
				alert( c['error_ignore_friend_request_timeout'] );
			}
		});
	}

/* If the DOM's fully loaded. */
	$(function(){
		
		/* Setup ajax loading animation. */
			$( '#friendSearchForm' ).hv_ajax_loader({
				'cover_on': true
			});
		
		/* Submit handler for friend search form. */
			$( '#friendSearchForm' ).submit( function(e){
				e.preventDefault();
				submitFriendSearch();
			});
		
		/* Closing the result box. */
			$( '#friendsContainer div.close_search' ).click(function(){
				$( '#searchResults' ).animate( {'height': 0}, 200 );
				$( this ).fadeOut( 200 );
				searchbox_opened=!searchbox_opened;
			});
			
	});