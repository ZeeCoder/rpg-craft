<?php
	if (!function_exists('session_starter')) {
		function session_starter() {
			if ( !(session_id() && headers_sent()) ) {
				return session_start();
			}
			return false;
		}
	}

	class HVAlert {
		var $c;
		
		function __construct( $config = array() ) {
			
			$this->c = array_merge(
				array(
					'setup'            => true
					,'show_alert'      => true
					,'ok_text'         => 'Ok'
					,'cancel_text'     => 'Cancel'
				), $config );
			
			if ( $this->c['setup'] )
				$this->setup( $this->c['show_alert'] );
				
			if (session_starter()) {
				unset( $_SESSION['hv_alert'] );
				session_write_close();
			}
		}
		
		function setup( $show_alert = true ) {
			if ( session_starter() && isset( $_SESSION['hv_alert'] ) ) {
				$arr = $_SESSION['hv_alert'];
				echo '<div id="hv_alert_wrapper" class="'.$arr['type'].' '.( ( $show_alert ) ? 'active' : '' ).'"><div class="title">'.$arr['title'].'</div><div class="content">'.$arr['message'].'</div><div class="buttons"><div class="button ok '.(($arr['ok_btn'])?'':' inactive').'">'.$this->c['ok_text'].'</div><div class="button cancel">'.$this->c['cancel_text'].'</div></div></div>';
				session_write_close();
			} else
				echo '<div id="hv_alert_wrapper"><div class="title">'.@$arr['title'].'</div><div class="content"></div><div class="buttons"><div class="button ok '.((@$arr['ok_btn'])?'':' inactive').'">'.$this->c['ok_text'].'</div><div class="button cancel">'.$this->c['cancel_text'].'</div></div></div>';
		}
		
		function add_alert( $arr = array() ) {
			if (!session_starter()) return;
			
				if ( !is_array( $arr ) )
					$arr = array( 'message' => $arr );
				
				$arr = array_merge(
					array(
						'title'          => 'Info'
						,'type'          => 'info'
						,'message'       => NULL
						,'ok_btn'        => false
					), $arr );
			
				$_SESSION['hv_alert'] = $arr;

			session_write_close();
			
		}
		
	}
	
?>
