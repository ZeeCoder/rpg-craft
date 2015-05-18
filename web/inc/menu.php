<?php
	
	if ( isset( $sess_user_id ) )
		$menupoints = array( 'news', 'friends', 'games', 'improvements' );
	else
		$menupoints = array( 'news', 'improvements', 'developers', 'login', 'registration' );
		
	$given_pages = $hvt->c['pages'];
	
	foreach ( $menupoints as $name ) {
		$authored = USER_RIGHT >= $given_pages[$name][PAGE_LANG]['needed_right'];
		if ( $authored ) {
			$url = PAGE_URL_ROOT.$given_pages[$name][PAGE_LANG]['name'].'/';
		?>
		<div class="button"><a href="<?php echo $url; ?>"><?php echo $given_pages[$name][PAGE_LANG]['full_name']; ?></a></div>
		<?php
		}
	}
	
	if ( isset( $sess_user_id ) )
		echo '<div class="button"><a href="'.PAGE_ROOT.'call/logout.php">'.$l_config['logout'].'</a></div>';
		
?>