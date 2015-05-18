<?php
	include_once("mysql_handler.php");
?>

<h2><?php echo $l_config["games_menu_title"]; ?></h2>
<?php
	$all_games = parse_ini_file("charsheet_xml/all_games_list.ini", true);
	
	$supported_games = parse_ini_file("charsheet_xml/supported_games_list.ini", true);
	$installed_ini = "charsheet_xml/installed_games_list_".$sess_user_id.".ini";
	if (is_file($installed_ini)) {
		$installed_games = parse_ini_file($installed_ini, true);
		$merged_games = array_unique($installed_games+$supported_games);
	}
	else $merged_games = $supported_games;
?>
<?php $db = new MysqlHandler(); $db->start();?>


<div id="create_install_game_btn">
	<div onclick="new_game_show_toggle();"><?php echo $l_config["games_start_game_button"]; ?></div>
	<div onclick="create_game_show_toggle();"><?php echo $l_config["games_install_submit"]; ?></div>
</div>

<!--<div class="new_game_btn" onclick="new_game_show_toggle();"><?php echo $l_config["games_start_game_button"]; ?></div>-->
<div id="newGame" class="round10">
	<div class="x" onclick="new_game_show_toggle();">X</div>
	<!--<div class="newGameButton"><?php echo $l_config["games_start_game_button"]; ?></div>-->
	<form method="post" action="lobby/">
		<div class="label"><?php echo $l_config["games_start_type"]; ?></div>
		<div>
			<select id="cg_type" name="type" class="type" class="type">
				<?php
					foreach ($merged_games as $key => $data) {
						$data = explode("-", $data);
						echo "<option value=\"".$key."\">".$data[1]."</option>";
					}
				?>
			</select>
		</div>
		<div class="label"><?php echo $l_config["games_start_game_title"]; ?></div>
		<div>
			<input id="cg_title" type="text" class="title" name="title" maxlength="100" />
		</div>
		<div class="label"><?php echo $l_config["games_start_game_description"]; ?></div>
		<div>
			<textarea id="cg_desc" class="description" name="description" maxlength="1200"></textarea>
		</div>
		<div>
		<input type="button" name="submitGameStart" onclick="create_new_game();" value="<?php echo $l_config["games_start_game_submit"]; ?>" />
		</div>
	</form>
</div>
<div id="game_install" class="round10">
	<div class="x" onclick="create_game_show_toggle();">X</div>
	<form method="post" id="game_install_form" action="">
		<div class="label"><?php echo $l_config["games_install_type"]; ?></div>
		<div>
			<select name="content" class="type" class="type">
				<option value="-">-</option>
				<?php
					$installable_games = array_diff($all_games, $merged_games);
					$have_installable = count($installable_games) > 0;
					if ($have_installable) {
						foreach ($installable_games as $key => $data) {
							$title = explode("-", $data);
							echo "<option value=\"".$key." = ".$data."\">".$title[1]."</option>";
						}
					}
				?>
			</select>
		</div>
	</form>
	<div class="newGameButton" onclick="<?php if($have_installable) { ?>install_new_game();<?php } ?>"><?php echo $l_config["games_install_submit"]; ?></div>
</div>

<h3><?php echo $l_config["games_as_gamemaster"]; ?></h3>

<?php

	$query = "SELECT gamesID, title, started, type FROM games WHERE usersID = '".$sess_user_id."'";
	
	$result = mysql_query($query);
	echo "<div id=\"gamesWrapper\" class=\"round10\">";
	if (mysql_num_rows($result) == 0) {
		echo "<div class=\"gamesRow round5\">";
			echo "<div class=\"gamesForeverAlone\" class=\"round5\">".$l_config["games_started"]."</div>";
		echo "</div>";
	} else {
		while ($row = mysql_fetch_assoc($result)) {
			$game_type = $all_games[$row['type']]; $game_type = explode("-", $game_type); $game_type = $game_type[1];
			echo "<a href=\"".PAGE_ROOT."game/?id=".$row["gamesID"]."\"><div class=\"gamesRow round5\">";
				echo "<div class=\"gamesRowID\">".$row['gamesID']."</div>";
				echo "<div class=\"gamesRowTitle\">".mb_substr($row['title'], 0, 30, 'UTF-8');
					if (strlen($row['title']) > 30) {
						echo "...";
					}
				echo "</div>";
				echo "<div class=\"gamesRowType\">".$game_type."</div>";
				echo "<div class=\"gamesRowStarted\">".$row['started']."</div>";
			echo "</div></a>";
		}
	}
	echo "</div>";
	
?>

<h3><?php echo $l_config["games_as_player"]; ?></h3>

<?php
	
	echo "<div id=\"gamesWrapper\" class=\"round10\">";
	$folder_arr = glob("game/game_folders/[0-9]*/user_".$sess_user_id.".txt");
	if (is_array($folder_arr)&&!empty($folder_arr)) {
		$ids = array();
		foreach ($folder_arr as $filename) {
			$game_id = explode("/", $filename);
			$ids[] = $game_id[2];
		}
		//print_r($ids);
		$ids = join("','", $ids);
		$query = "SELECT * FROM `games` WHERE `gamesID` IN ('$ids') AND `usersID` != '".$sess_user_id."'";
		$result = mysql_query($query);
		
		
		while ($row = mysql_fetch_assoc($result)) {
			$game_type = $all_games[$row['type']]; $game_type = explode("-", $game_type); $game_type = $game_type[1];
			echo "<a href=\"".PAGE_ROOT."game/?id=".$row["gamesID"]."\"><div class=\"gamesRow ";
			echo " round5\" id=\"ongoing_".$row['gamesID']."\">";
				echo "<div class=\"gamesRowID\">".$row['gamesID']."</div>";
				echo "<div class=\"gamesRowTitle\">".substr($row['title'], 0, 30);
					if (strlen($row['title']) > 30) {
						echo "...";
					}
				echo "</div>";
				echo "<div class=\"gamesRowType\">".$game_type."</div>";
				echo "<div class=\"gamesRowStarted\">".$row['started']."</div>";
			echo "</div></a>";
		}
	} else {
		echo "<div class=\"gamesRow round5\">";
			echo "<div class=\"gamesForeverAlone\" class=\"round5\">".$l_config["games_as_player_empty"]."</div>";
		echo "</div>";
	}
	echo "</div>";
	
?>
<?php $db->stop(); ?>