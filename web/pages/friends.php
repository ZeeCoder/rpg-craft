<?php
	include_once("mysql_handler.php");
?>
<h2><?php echo $l_config["friends_menu_title"]; ?></h2>
<div id="friendsContainer">
	<div class="close_search">X</div>
	<div class="search round5" id="search">
		<form id="friendSearchForm" method="post">
			<div class="disable"></div>
			<input type="text" id="keyWord" name="keyWord" class="round5" placeholder="<?php echo $l_config["friends_search_placeholder"]; ?>" />
		</form>
		<div id="searchResults"></div>
	</div>
	<div class="approvedFriends round5">
		<?php
			$db = new MysqlHandler();
			$db->start();
				$query = "
					SELECT * FROM users WHERE usersID IN (
						(SELECT initiaterID FROM friendconnections WHERE accepterID = '".$sess_user_id."' AND approved = 1)
					)
					UNION
					SELECT * FROM users WHERE usersID IN (
						(SELECT accepterID FROM friendconnections WHERE initiaterID = '".$sess_user_id."' AND approved = 1)
					)";
				$table = mysql_query($query);
				if (mysql_num_rows($table) == 0) {
					echo "<div class=\"foreverAlone round5\">".$l_config["friends_empty"]."</div>";
				} else {
					while ($row = mysql_fetch_assoc($table)) {
						echo "<div class=\"listedFriend round5\" id=\"friend_del_".$row["usersID"]."\">";
							echo "<div class=\"nick\">".$row["nick"]."</div>";
							// echo "<div class=\"button\">".$l_config["friends_send_message"]."</div>";
							echo "<div class=\"friend_request button\" onclick=\"delete_friend('".$row["usersID"]."')\">".$l_config["friends_delete_friend"]."</div>";
						echo "</div>";
					}
				}
			$db->stop();
		?>
	</div>
</div>