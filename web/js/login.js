
/* Shows the appropriate menu. */
	function loginMenu( id ) {
		$( 'div.loginLeftSide' ).css( 'display', 'none' );
		$( '#loginMenu'+id ).css( 'display', 'block' );
	}

/* If the DOM's fully loaded. */
	$(function(){
		
		/* Login form validation. */
			$( '#loginForm' ).validate({
				rules: {
					loginMail: {
						required: true
						,email: true
					},
					loginPass: 'required'
				}
				,messages: {
					loginMail: {
						required: c['form_empty_field']
						,email: c['form_mail_needed']
					}
					,loginPass: c['form_empty_field']
				}
			});
		
		/* Forgotten e-mail form validation. */
			$( '#forgottenForm' ).validate({
				rules: {
					forgottenMail: {
						required: true
						,email: true
					}
				}
				,messages: {
					forgottenMail: {
						required: c['form_empty_field']
						,email: c['form_mail_needed']
					}
				}
			});
		
		/* Activation form validation. */
			$( '#activationForm' ).validate({
				rules: {
					activationMail: {
						required: true
						,email: true
					}
				}
				,messages: {
					activationMail: {
						required: c['form_empty_field']
						,email: c['form_mail_needed']
					}
				}
			});
	});