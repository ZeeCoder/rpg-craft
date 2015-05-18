
/* If the DOM's fully loaded. */
	$(function(){
		
		/* Datepicker for the profile form's birth entry. */
			$( '#registerBorn' ).datepicker({
				dateFormat: 'yy-mm-dd'
				,yearRange: 'c-100:c'
				,changeMonth: true
				,changeYear: true
				,minDate: '-100y'
				,maxDate: 0
				,onSelect: function(){
					$( this ).addClass( 'valid' );
				}
			});
			$( '#registerBornWrapper' ).click(function(){
				$( '#registerBorn' ).datepicker( 'show' );
			});
	
		/* Validate the profile form. */
			$( '#registerForm' ).validate({
				rules: {
					registerMail: {
						required: true
						,email: true
					}
					,registerName: 'required'
					,registerBorn: 'required'
					,registerPass: 'required'
					,registerPassConfirm: {
						required: true
						,equalTo: '#registerForm #registerPass'
					}
				}
				,messages: {
					registerMail: {
						required: c['form_empty_field']
						,email: c['form_mail_needed']
					}
					,registerName: c['form_empty_field']
					,registerBorn: c['form_empty_field']
					,registerPass: c['form_empty_field']
					,registerPassConfirm: {
						required: c['form_empty_field']
						,equalTo: c['form_match_pass']
					}
				}
			});
		
		/* Submitting the profile update form. */
			$( '#registerForm' ).submit(function(e){
				e.preventDefault();
				var valid = $( '#registerForm' ).valid();
				if ( valid )
					$.ajax({
						url: PAGE_ROOT+'call/update_profile.php'
						,type: 'POST'
						,data: $( '#registerForm' ).serialize()
						,success: function(data){
							if ( data=='1' )
								$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
									'message': c['successful_update']
								});
							else
								$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
									'message': c['unsuccesful_update']+' '+data
								});
						}
						,error: function(){
							$( '#hv_alert_wrapper' ).hv_alert( 'show_alert', {
								'message': c['unsuccesful_update']
							});
						}
					});
			});
			
	});