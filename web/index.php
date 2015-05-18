<?php
	session_start();
		$sess_user_name = $_SESSION['user_name'];
		$sess_user_mail = $_SESSION['user_mail'];
		$sess_user_born = $_SESSION['user_born'];
		$sess_user_gender = $_SESSION['user_gender'];
		$sess_saved_reg_data = $_SESSION['saved_reg_data'];
			unset($_SESSION['saved_reg_data']);
		if(isset($_SESSION['user_id']))
			$sess_user_id = $_SESSION['user_id'];

		/* Támogatot nyelvek SESSION-be töltése */
			$l_supported = parse_ini_file('supported_langs.ini', true);
			$l_supported = explode(',', $l_supported['supported_langs']);
			$_SESSION['supported_lang'] = $l_supported;
	session_write_close();

	/* Include HVTemplate and Routing */
		require_once( 'plugins/hv_routing.php' );
		require_once( 'plugins/hv_template.php' );
		require_once( 'plugins/hv_template_config.php' );
		require_once( 'plugins/hv_alert/hv_alert.php' );
		require_once( 'config_loader.php' );
	
	
	/* Start HV-Template */
		try {
			
			$hvt = new HVTemplate( $hv_template_config );

			new HVAlert();
			
		} catch (Exception $e) {
			die( $e->getCode() . ': ' . $e->getMessage() );
		}
		$config = parse_ini_file('config.ini', true);
		
	
	/*Nyelvi adatok betöltése*/
		session_start();
			$_SESSION['PAGE_ROOT'] = PAGE_ROOT;
			$_SESSION['redirect_to'] = $_SERVER['REDIRECT_URL'];

			$_SESSION['lang'] = $hvt->get_lang();
			$sess_lang = $hvt->get_lang();
			$l_config = config_loader( array(
				'langs/l_main'
				,'langs/l_login'
			) );
		session_write_close();
	
?>
	<div id="the_black_cloud"></div>
	
	
	<div id="langs">
		<div class="label"><?php echo $l_config["lang_label"]; ?></div>
		<?php include( 'inc/ch_lang.php' ); ?>
	</div>
	
	<?php if ( isset( $sess_user_id ) ) { ?>
		<div id="newMessage">
			<div class="label"><?php echo $l_config["requests_label"]; ?></div>
			<div class="content"></div>
		</div>
	<?php } ?>
	
	<div id="mainWrapper" class="round10">
		<div id="banner">
			<h1 class="logo">RPGCraft</h1>
			<?php
				if ( isset( $sess_user_id ) ) {
					echo $l_config["user_welcome"].$sess_user_name."!<br/>";
					echo "<span><a href='".$hvt->get_page_link( 'profile' )."/'>".$l_config["menu_profil"]."</a></span><br/>";
					echo "<span><a href='".$hvt->get_page_link( 'developers' )."/'>".$l_config["menu_developers"]."</a></span>"; //window.open('validate_tool.php');
				}
			?>
			<div id="menu"><?php include( 'inc/menu.php' ); ?></div>
		</div>
		<div id="body">
			<div class="content_wrapper">
				<div class="content_slider">
					<div class="content">
						<?php
						/* Load actual page */
							try {
								
								//session_start();
									include( $hvt->load_current_page() );
								//session_write_close();
								
							} catch (Exception $e) {
								echo $e->getCode() . ': ' . $e->getMessage();
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Language -->
		<script src="<?php echo PAGE_ROOT; ?>langs/config_<?php echo PAGE_LANG; ?>.js" type="text/javascript"></script>
		<script>var c = c_def;</script>
	<!-- Language -->
	
	<!-- Google Analytics -->
		<?php
			$ga = ( isset( $sess_user_id ) ) ? 'ga_user.js': 'ga_visitor.js';
		?>
		<script src="<?php echo PAGE_ROOT; ?>js/<?php echo $ga; ?>" type="text/javascript"></script>
	<!-- Google Analytics -->
	
<?php
/* Setup footer */
	$hvt->setup_footer();
?>