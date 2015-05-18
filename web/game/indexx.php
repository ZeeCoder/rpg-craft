<?php
/**
	@author: Hubert Viktor
	@since: 2012
	@description: A játék indexe. / game /
*/

/* Starting session. */
	session_start();

/* Include necessary scripts. */
	include_once('../auth.php');
	include_once('../mysql_handler.php');
	require_once('../plugins/config_loader.php');
	include_once( '../plugins/hv_alert/hv_alert.php' );

	/* Load HV Alert plugin. */
		$hva = new HVAlert( array(
			'setup' => false
		) );
	
/* Load Config file */
	$l_config = config_loader('../langs/l_game');
	
/*Checking for game ID.*/
	if (!isset($_SESSION['game_id']) && !isset($_GET['id'])) {
		$hva->add_alert( $l_config['game_missing_id'] );
		header( 'Location: '.$_SESSION['redirect_to'] );
		exit();
	}

/* Saving game ID. */
	$_SESSION['game_id'] = $_GET['id'];
	
/* Check if game exists with given ID. */
	if (!is_dir('game_folders/'.$_SESSION['game_id'])) {
		$hva->add_alert( $l_config['game_non_existent'] );
		header( 'Location: '.$_SESSION['redirect_to'] );
		exit();
	}

/* Check if actual user is an invited gamer of the given game. */
	if (!is_file('game_folders/'.$_SESSION['game_id'].'/user_'.$_SESSION['user_id'].'.txt')) {
		$hva->add_alert( $l_config['game_not_participant'] );
		header( 'Location: '.$_SESSION['redirect_to'] );
		exit();
	}
	
	
/* Collecting data about the game from the database. */
	$db = new MysqlHandler();
	$db->start();
		$query = "SELECT * FROM games WHERE gamesID = '".$_SESSION['game_id']."'";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$_SESSION['game_title'] = $row['title'];
		$_SESSION['game_owner_id'] = $row['usersID'];
		$_SESSION['game_desc'] = $row['description'];
		$_SESSION['game_type'] = $row['type'];
		$_SESSION['game_created'] = $row['started'];
	$db->stop();
	
/* Setup constants. */
	define('PAGE_ROOT', $_SESSION['PAGE_ROOT']);
	define('PAGE_LANG', $_SESSION['lang']);
	
/* Get game type name. */
	$ini_source = '../charsheet_xml/installed_games_list_'.$_SESSION['user_id'].'.ini';
	$all_games = parse_ini_file('../charsheet_xml/all_games_list.ini', true);
	$game_type = $all_games[$row['type']]; $game_type = explode('-', $game_type);
	$_SESSION['game_type_name'] = $game_type[0];
	$_SESSION['game_type_full_name'] = $game_type[1];
	
/* Checking if actual user is the GM of the given game? */
	if (is_file('game_folders/'.$_SESSION['game_id'].'/gm.txt')) {
		$f = fopen('game_folders/'.$_SESSION['game_id'].'/gm.txt', 'r');
			$gm_id = fgets($f, 4096);
		fclose($f);
		if ($gm_id==$_SESSION['user_id']) $_SESSION['act_user_is_gm']=true;
		else $_SESSION['act_user_is_gm']=false;
	}
	
	
/* Loading charsheet information. */
	$charsheet_info_src = '../charsheet_xml/'.$_SESSION['game_type_name'].'/lang_'.$_SESSION['game_type_name'];
	$charsheet_info = config_loader($charsheet_info_src);
	
/* Loading charsheet configuration. */
	$conf_ini_url = '../charsheet_xml/'.$_SESSION['game_type_name'].'/conf_'.$_SESSION['game_type_name'];
	$base_conf_ini_url = '../charsheet_xml/base_conf';
	$charsheet_conf = config_loader(array($conf_ini_url, $base_conf_ini_url));
	
	
/* Preloading character sheet for better performance. */
	require('xml2array.php');
	$xml_src = '../charsheet_xml/'.$_SESSION['game_type_name'].'/'.$_SESSION['game_type_name'].'.xml';
	if (!is_file($xml_src)) {
		$xml_any = glob('../charsheet_xml/'.$_SESSION['game_type_name'].'/*.xml');
		if (is_array($xml_any)&&!empty($xml_any))
			$xml_src = $xml_any[0];
	}
	$arr = xml2array($xml_src);
	$arr['charsheet'] = record_correction($arr['charsheet']);
	$_SESSION['arr']=$arr;
	
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<title>RPGCraft - '<?php echo $_SESSION['game_title']; ?>'</title>
	
	<script src='../js/jquery.min.js' type='text/javascript'></script>
	<link href='../favicon.ico' type='image/x-icon' rel='shortcut icon' />
	
	<!-- STYLES -->
		<link href='../js/jquery-ui-1.10.0.custom/css/smoothness/jquery-ui-1.10.0.custom.min.css' type='text/css' rel='stylesheet' />
		<link href='../charsheet_xml/char_xml.css' type='text/css' rel='stylesheet' />
		<link href='../css/game.css' type='text/css' rel='stylesheet' />
		<link href='../plugins/hv_alert/hv_alert.css' type='text/css' rel='stylesheet' />
		<link href='../plugins/hv_ajax_loader/hv_ajax_loader.css' type='text/css' rel='stylesheet' />
		<?php
			/* Searching for stylesheets provided by the character sheet. */
				$css_src = '../charsheet_xml/'.$_SESSION['game_type_name'].'/'.$_SESSION['game_type_name'].'.css';
				if ( !is_file( $css_src ) ) {
					$any_css = glob('../charsheet_xml/'.$_SESSION['game_type_name'].'/*.css');
					if (is_array($any_css)&&!empty($any_css))
						$css_src = $any_css[0];
				}
				if ( is_file( $css_src ) )
					echo '<link rel="stylesheet" type="text/css" href="'.$css_src.'" />';
		?>
	<!-- STYLES -->
	
	<!-- VARIABLES -->
		<script type='text/javascript'>
			var gm_id = <?php echo $_SESSION['game_owner_id']; ?>;
			var game_id = <?php echo $_SESSION['game_id']; ?>;
			var act_user_id = <?php echo $_SESSION['user_id']; ?>;
			var act_user_is_gm = <?php echo ($_SESSION['act_user_is_gm'])?'true':'false'; ?>;
			var REDIRECT_TO = '<?php echo PAGE_ROOT.PAGE_LANG; ?>';
			
			var charsheet_noresize = <?php echo (isset($charsheet_conf['charsheet_noresize']))?$charsheet_conf['charsheet_noresize']:'false'; ?>;
			var resize_min_width = <?php echo (isset($charsheet_conf['resize_min_width']))?$charsheet_conf['resize_min_width']:'200'; ?>;
			var resize_max_width = <?php echo (isset($charsheet_conf['resize_max_width']))?$charsheet_conf['resize_max_width']:'null'; ?>;
			var resize_min_height = <?php echo (isset($charsheet_conf['resize_min_height']))?$charsheet_conf['resize_min_height']:'200'; ?>;
			var resize_max_height = <?php echo (isset($charsheet_conf['resize_max_height']))?$charsheet_conf['resize_max_height']:'null'; ?>;
		</script>
	<!-- VARIABLES -->
	
	<!-- Language -->
		<script src='../langs/config_<?php echo PAGE_LANG; ?>.js' type='text/javascript'></script>
		<script>var c = c_def;</script>
	<!-- Language -->
	
</head>

<body>
	<?php
		/* Load HV Alert plugin. */
			new HVAlert();
	?>
	
	<div id='ping_wrapper'>
		<div class='dragger'></div>
		<div class='manual'></div>
		<div class='manual_sync_now'></div>
		<div class='ping_container'></div>
	</div>
	<iframe id='hiddenFrame' name='hiddenFrame' src=''></iframe>
	<form id='upload_map' method='post' action='upload_map.php' enctype='multipart/form-data' target='hiddenFrame'>
		<input type='hidden' name='MAX_FILE_SIZE' value='3000000' />
		<input id='file' type='file' name='files[]' accept='image/jpeg' multiple />
		<input type='submit' />
	</form>
	<form id='entries_upload' method='post' action='up_pics.php' enctype='multipart/form-data' target='hiddenFrame'>
		<input type='hidden' name='MAX_FILE_SIZE' value='3000000' />
		<input type='hidden' name='entry_id' />
		<input id='entry_files' type='file' name='files[]' accept='image/jpeg' multiple>
		<input type='submit' />
	</form>
	
	<div id='add_new_entry_wrapper'>
		<form id='add_new_entry_form' method='' action='' target=''>
		<input placeholder='<?php echo $l_config['entry_title']; ?>' type='text' name='title' />
		<textarea name='desc' placeholder='<?php echo $l_config['entry_desc']; ?>'></textarea>
		<div class='selecting'>
			<div><?php echo $l_config['entry_visibility']; ?></div>
			<div>
				<input type='radio' name='select_type' value='all' checked='checked' /><?php echo $l_config['entry_vis_all']; ?>
				<input type='radio' name='select_type' value='none' /><?php echo $l_config['entry_vis_none']; ?>
				<input type='radio' name='select_type' value='other' id='othersel' /><?php echo $l_config['entry_vis_other']; ?>
			</div>
			<div class='selectable_list_wrap'><div class='selectable_list_cont'></div></div>
		</div>
		</form>
		<div class='submit'><?php echo $l_config['entry_upload']; ?></div>
		<div class='exit'>X</div>
		<div class='dragger'></div>
		<div class='disable'></div>
	</div>
	<div id='show_entry_wrapper'>
		<div class='title'<?php if ($_SESSION['act_user_is_gm']) echo ' contenteditable="true"'; ?>></div>
		<div class='desc'<?php if ($_SESSION['act_user_is_gm']) echo ' contenteditable="true"'; ?>></div>
		<?php if ($_SESSION['act_user_is_gm']){ ?>
		<div class='updating'><?php echo $l_config['entry_update_btn']; ?></div>
		<div class='selecting'>
			<div><?php echo $l_config['entry_visibility']; ?></div>
			<div>
				<input type='radio' name='select_type' value='all' checked='checked' /><?php echo $l_config['entry_vis_all']; ?>
				<input type='radio' name='select_type' value='none' /><?php echo $l_config['entry_vis_none']; ?>
				<input type='radio' name='select_type' value='other' id='othersel_show' /><?php echo $l_config['entry_vis_other']; ?>
			</div>
			<div class='selectable_list_wrap'><div class='selectable_list_cont'></div></div>
		</div>
		<?php } ?>
		<div class='dragger'></div>
		<div class='exit'>X</div>
		<div class='entrypics_wrap' id='entrypics_wrap'><div id='entrypics_cont'></div></div>
		<div class='disable'></div>
		<?php
			if ($_SESSION['act_user_is_gm']) {
		?>
			<div id='entry_pic_up_button' class='submit'><?php echo $l_config['entry_up_pic']; ?></div>
		<?php
			}
		?>
	</div>
	
	<div id='loading_screen'>
	<div class='title'><?php echo $l_config['loading']; ?></div>
		<div class='gm_offline'><?php echo $l_config['error_waiting_for_gm']; ?><div onclick='window.location = "../main";'><?php echo $l_config['error_leave_lobby']; ?></div></div>
	</div>
	<div id='allWrapper' class=''>
		<div id='info'>
			<div onclick='show_desc("game_desc");' class='game_desc_short'><?php echo $_SESSION['game_title']; ?></div>
			<div onclick='show_desc("charsheet_desc");' class='charsheet_desc_short'><?php echo $_SESSION['game_type_full_name']; ?></div>
			<div class='dragger'></div>
		</div>
		<div id='desc_container'>
			<div class='game_desc desc_cont'>
				<?php echo $l_config['game_desc_title']; ?><br/><br/>
				<?php echo $_SESSION['game_desc']; ?></div>
			<div class='charsheet_desc desc_cont'>
				<?php echo $l_config['charsheet_desc_title']; ?><br/><br/>
				<?php echo $l_config['charsheet_author']; ?>: <?php echo $charsheet_info['author']; ?><br/>
				<?php echo $l_config['charsheet_dices']; ?>: <?php echo $charsheet_conf['dices']; ?><br/>
				<?php echo $l_config['charsheet_desc']; ?>: <?php echo $charsheet_info['desc']; ?>
			</div>
			<div class='dragger'></div>
			<div class='x' onclick='show_desc("x");'>X</div>
		</div>
		<?php
			if ($_SESSION['act_user_is_gm']) {
		?>
			<div id='friends_to_invite'>
				<div class='not_invited_yet'></div>
				<div class='already_invited'></div>
				<div class='dragger'></div>
				<div class='x' onclick='show_inviteables();' style='right: 30px;'>X</div>
			</div>
		<?php
			}
		?>
		<div class='eventsWrapper' id='eventsWrapper'>
			<div class='map_container'>
				<div class='title'><?php echo $l_config['label_maps']; ?>
				<?php
					if ($_SESSION['act_user_is_gm']) echo ' - <span onclick=\'add_new_map();\'>'.$l_config['gm_new_map'].'</span>';
				?>
				</div>
				<div class='mapWrapper'></div>
			</div>
			
			<div class='entry_container'>
				<div class='title'><?php echo $l_config['label_entries']; ?>
				<?php
					if ($_SESSION['act_user_is_gm']) echo ' - <span onclick=\'add_new_event_toggle();\'>'.$l_config['gm_new_event'].'</span>';
				?>
				</div>
				<div id='entry_wrapper' class='entryWrapper'></div>
			</div>
			<div class='menu_container'>
				<?php
					/*
					Deprecated, not needed anymore.
					if ($_SESSION['act_user_is_gm'])
						echo '<div class="menu" onclick="game_logout();">'.$l_config['exit'].'</div>';
					else
						echo '<div class="menu" onclick="window.location=\''.PAGE_ROOT.PAGE_LANG.'\'">'.$l_config['exit'].'</div>';
					*/
				?>
				<div class="menu" onclick="window.location='<?php echo PAGE_ROOT.PAGE_LANG; ?>'"><?php echo $l_config['exit']; ?></div>
			</div>
		</div>
		<div class='map' id='map'>
			<div class='container' id='mapimg'>
				<div class='disable'></div>
				<img src='../img/main_bg.jpg' />
			</div>
		</div>
		<div id='character_sheet' class='<?php echo $_SESSION['game_type_name'].'_border_style'; ?>'>
			<div class='x'>X</div>
			<div class='dragger'></div>
			<div class='wrapper <?php echo $_SESSION['game_type_name'].'_bg_style'; ?>' id='charsheet_wrapper'>
				<div class='content'></div>
			</div>
		</div>
		<div class='diceWrapper' class=' '>
			<div class='dragger'></div>
			<form id='diceForm' method='post' autocomplete='off'>
				<input type='text' class='first' name='d' maxlength='100' placeholder='<?php echo $l_config['dice_page_num']; ?>' />
				<input type='text' class='second' name='db' maxlength='100' placeholder='<?php echo $l_config['dice_throw_num']; ?>' />
				<input type='button' onclick='dice();' value='<?php echo $l_config['dice_throw']; ?>' />
			</form>
		</div>
		<div class='chatWrapper' class=' '>
			<div class='dragger'></div>
			<div id='chatBox' class='chatBox also_resize'>
				<div id='chatMessages' class='also_resize'></div>
			</div>
			<form id='chatForm' method='post' autocomplete='off'>
				<input type='text' id='chat_message' class='also_resize' name='message' maxlength='500' />
			</form>
		</div>
		<div id='gamersWrapper' class=' '>
			<div class='dragger'></div>
			<?php
				if ($_SESSION['act_user_is_gm']) {
			?>
			<div class='friend_inviter_btn' onclick='show_inviteables();'>+</div>
			<?php
				}
			?>
			<div class='gamers_content'></div>
		</div>
	</div>
	
	<!-- SCRIPTS-->
		<script src='../js/jquery-ui-1.10.0.custom/js/jquery-ui-1.10.0.custom.min.js' type='text/javascript'></script>
		<script src='../plugins/jquery.validate.js' type='text/javascript'></script>
		<script src='../plugins/jquery.cookie.js' type='text/javascript'></script>
		<script src='../plugins/hv_ajax_loader/hv_ajax_loader.js' type='text/javascript'></script>
		<script src='../plugins/hv_alert/hv_alert.js' type='text/javascript'></script>
		
		<script src='game.js' type='text/javascript'></script>
		<script src='../charsheet_xml/index.js' type='text/javascript'></script>
		
		<script src='../js/ga_ingame.js' type='text/javascript'></script>
	<!-- SCRIPTS -->
	
</body>
</html>