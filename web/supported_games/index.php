<?php

	/* Default mode. */
		$def_mode = 'rss';

	/* Setting the requested output mode. */
		$modes = array( 'text', 'json', 'rss' ); //List of possible modes.
		$m = isset( $_GET[ 'format' ] ) ? $_GET[ 'format' ] : $def_mode; //If the mode is not set explicitly, set it to text.
	
	/* Check if the got m parameter is in the allowed parameters. If not, set it to the default 'text' value. */
		if( !in_array($m, $modes) )
			$m = $def_mode;

	/* Getting the data, and putting it to arrays. */
		$all_url = '../charsheet_xml/all_games_list.ini';
		$all = parse_ini_file( $all_url, true );
	
		$all_arr = array();
		foreach ( $all as $line ) {
			$name = explode( '-', $line );
			$name = $name[1];
			$all_arr[] = $name;
		}
		
		$installed_url = '../charsheet_xml/supported_games_list.ini';
		$installed = parse_ini_file( $installed_url, true );
		$installed_arr = array();
		foreach ( $installed as $line ) {
			$name = explode( '-', $line );
			$name = $name[1];
			$installed_arr[] = $name;
		}
	
	
	/* Outputting data. */
		if ( $m=='json' ) {
		
			/* Outputting data in JSON format. */
			header( 'Content-type: application/json' );
			echo json_encode( array(
				'all_games' => $all_arr
				,'installed_games' => $installed_arr
			) );
			
		}
		else if ( $m=='rss' ) {
			
			/* Outputting data in RSS format. */
			header( 'Content-type: text/xml' );
			echo '<?xml version="1.0" encoding="utf-8"?>';
			
			/* Get latest modifycation */
			$all_time = filemtime( $all_url );
			$installed_time = filemtime( $installed_time );
			$updated = ( $all_time > $installed_time ) ? date( 'Y-m-d H:i', $all_time ) : date( 'Y-m-d H:i', $installed_time );
			?>
			
			<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
			
			<channel>
				<title>RPGCraft</title>
				<description>A page, where you can craft your own rpgs and play them for free. There're also a lot of other games already crafted you can choose of.</description>
				<link>http://hv-web.hu/rpg/</link>
				<atom:link href="http://hv-web.hu/rpg/supported_games/" rel="self" type="application/rss+xml" />
				
				<item>
					<title>List of games</title>
					<description>
						<?php
							echo 'All games: '.implode( ', ', $all_arr );
							echo '<![CDATA[<br/>]]>';
							echo 'Default installed games: '.implode( ', ', $installed_arr );
						?>
					</description>
					<link>http://hv-web.hu/rpg/</link>
					<pubDate><?php echo $updated; ?></pubDate>
					<guid>http://hv-web.hu/rpg/</guid>
				</item>
				
			</channel>
			
			</rss>
			<?php
			
		}
		else if ( $m=='text' ) {
			
			/* Outputting data in plain text format. */
			header( 'Content-type: text/plain' );
			echo "all games\n";
				foreach ( $all_arr as $name )
					echo $name."\n";
					
			echo "\ninstalled games by default\n";
				foreach ( $installed_arr as $name )
					echo $name."\n";
					
		}
	
?>