<?php
	/*
		=============================================================
		RPGCraft ENGINE
		Készítette: Hubert Viktor
		Minden jog fenntartva.
		=============================================================
	
		index.php
			A karakterlapot legeneráló fájl.
			A game-ben SESSION-be betöltött XMl-t dolgozza fel, hozzáépítve a CSS Classokat, javascript kódokat és adatbázis adatokat.
		
		Paraméterek
			Kétféle meghívása létezik:
			
			"Normál mód".
				Ekkor történik a tényleges karaktergenerálás. (Itt a paraméterek többsége az aktuális játék SESSION-ből származik.)
			- user_id: A lekért játékos id-je.
			
			"Néző" mód.
				Ekkor A karakterlapot nem tölti ki adatokkal, csak megnézésre, egy funkciómentes karakterlapot generál.
			- game_type: A játék típusa, pl: 'example_10'.
			- viewer_mode: Ez kapcsolja be a Néző módot.
		
		Visszatérési érték:
			A karakterlap.
	*/
	session_start();
	include("../mysql_handler.php");
	if (isset($_SESSION["pass"]) && $_SESSION["pass"] == "Tr2CRAcr") {
		
		if 
		(
		
			(
				!isset($_REQUEST["user_id"]) || 
				!isset($_SESSION["game_id"]) ||
				!isset($_SESSION["game_type"])
			)
			&&
			(
				!isset($_REQUEST["game_type"]) ||
				!isset($_REQUEST["viewer_mode"])
			)
			
		) exit();
		
		if (isset($_REQUEST["game_type"]))
			$xml_src = $_REQUEST["game_type"]."/".$_REQUEST["game_type"].".xml";
		else
			$xml_src = $_SESSION["game_type_name"]."/".$_SESSION["game_type_name"].".xml";
			
		
		$viewer_mode = isset($_REQUEST["viewer_mode"]);
		$game_type_name = $_SESSION["game_type_name"];
		$game_type = (isset($_REQUEST["game_type"]))?$_REQUEST["game_type"]:$_SESSION["game_type"];
		if ($viewer_mode) $game_type_name = $_REQUEST["game_type"];

		/*Ha a KM nézi egy másik user karakterlapját, erre az id-re kell feltöltenie.*/
			$_SESSION["watched_user_id"] = $_REQUEST["user_id"];
			$sess_watched_user_id = $_SESSION["watched_user_id"];

		/* Session adatok kinyerése */
			$sess_user_id = $_SESSION["user_id"];
			$sess_act_user_is_gm = $_SESSION["act_user_is_gm"];
			$game_owner_id = $_SESSION["game_owner_id"];
			$sess_game_id = $_SESSION["game_id"];

		/* Karakterlap előtöltése Sessionből feldolgozásra. */
			if (!$viewer_mode) {
				$ch_arr = $_SESSION["arr"]["charsheet"];
				$ch_attr = $_SESSION["arr"]["charsheet_attr"];
			}

		/* Session kikapcsolása */
			session_write_close();

		/*Viewer mód bekapcsolása.*/
		if ($viewer_mode) {
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<title>RPGCraft - "<?php echo $game_type_name; ?>"</title>
			
			<link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.19.custom.css" rel="Stylesheet" />
			<script src="../js/jquery-1.7.2.min.js" type="text/javascript"></script>
			<script type="text/javascript" src="../js/jquery-ui-1.8.19.custom.min.js"></script>
			<script type="text/javascript" src="../js/jquery.validate.js"></script>
			<?php
				$css_src = $game_type_name."/".$game_type_name.".css";
				if (!is_file($css_src)) {
					$any_css = glob($game_type_name."/*.css");
					if (is_array($any_css)&&!empty($any_css))
						$css_src = $any_css[0];
				}
				echo "<link rel='stylesheet' type='text/css' href='$css_src' />";
			?>
			<link rel="stylesheet" type="text/css" href="char_xml.css" />
			<link rel="stylesheet" type="text/css" href="../game/game.css" />
			
			<script type="text/javascript" src="../js/hv_ajax_loader.js"></script>
			<link rel="stylesheet" type="text/css" href="../css/hv_ajax_loader.css" />
			<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
		<body>
		<?php
		}
		
		/*Mód megállapítása*/
		$edit_mode = false;
		if ( $_REQUEST["user_id"] == $sess_user_id || $viewer_mode) $edit_mode = true;
		else if ($sess_act_user_is_gm) {$edit_mode = true; $gm_editing = true;}
		
		/*Config file betöltése*/
		require_once("../config_loader.php");
		$base_config = config_loader("l_char");
		
		/*Karakterlap nevének kikeresése*/
		$supported_games = parse_ini_file("supported_games_list.ini", true);
		$installed_ini = "installed_games_list_".$game_owner_id.".ini";
		
		if (is_file($installed_ini)) {
			$installed_games = parse_ini_file($installed_ini, true);
			$merged_games = array_unique($installed_games+$supported_games);
		}
		else $merged_games = $supported_games;
		
		$game_type = $merged_games[$game_type]; $game_type = explode("-", $game_type); $game_type = $game_type[0];
		
		/*Nyelv betöltése*/
		$xml_lang_conf = config_loader($game_type_name."/lang_".$game_type_name);
		
		/*Karakterlap előkészítése az aktuális felhasználó számára az adatbázisban.*/
		$db = new MysqlHandler();
		$db->start();
			if ($viewer_mode) {
				require("../game/xml2array.php");
				$xml_src = $_REQUEST["game_type"]."/".$_REQUEST["game_type"].".xml";
				if (!is_file($xml_src)) {
					$xml_any = glob($_REQUEST["game_type"]."/*.xml");
					if (is_array($xml_any)&&!empty($xml_any))
						$xml_src = $xml_any[0];
				}
				$ch_arr_i = xml2array($xml_src);
				$ch_arr = record_correction($ch_arr_i["charsheet"]);
				$ch_attr = $ch_arr_i["charsheet_attr"];
			}

			if (!$viewer_mode) {
				foreach ($ch_arr as $tagname => $tags) {
					if (strpos($tagname, "_attr")) continue;
					//echo $tagname."<br/>";
					if ($ch_arr[$tagname."_attr"]["limit"]!=1) continue;
					$query = "SELECT * FROM `s_".$game_type."_".$tagname."` WHERE `gamesID`='".$sess_game_id."' AND `usersID`='".$sess_watched_user_id."'";
					// echo $query;
					$result = mysql_query($query);
					if (mysql_num_rows($result) == 0) {
						$query = "INSERT INTO `s_".$game_type."_".$tagname."` (`gamesID`, `usersID`) VALUES('".$sess_game_id."', '".$sess_watched_user_id."')";
						mysql_query($query);
					}
				}
			}
		$db->stop();
	
		//Van-e már karaktere
		$db = new MysqlHandler();
		$db->start();
			
			/*charsheet attribútumainak feldolgozása*/
			/*Charsheet legenerálás*/
			echo "<div id='charsheet'>";

			$z_index = 100;
			/*Végigjárás a fő tag-eken.*/
			foreach ($ch_arr as $game_struct_element => $tags) {
				if (strpos($game_struct_element, "_attr")) continue;
				$tag_attrs = $ch_arr[$game_struct_element."_attr"]; /*tag attribútumai*/
				if ($game_struct_element == "description") continue;
				if ($game_struct_element == "break") continue;
				
				/*Ha csak díszítő sor, másként kezeljük.*/
				$design_row = (isset($tag_attrs["display"])&&$tag_attrs["display"]=="design");
				$game_struct_limit = (isset($tag_attrs["limit"])) ? $tag_attrs["limit"]: 0;
				if (!$design_row && !$viewer_mode) {
					$query = "SELECT * FROM `s_".$game_type."_".$game_struct_element."` WHERE `gamesID` = '".$sess_game_id."' AND `usersID` = '".$_REQUEST["user_id"]."' ORDER BY `specID` DESC";
					//echo $query;
					$result = mysql_query($query);
				}
				//echo $query;
				
				$record_id = 0;
				
				echo "<div id='".$game_struct_element."_to_drag' style='z-index: ".$z_index++.";"/*.(isset($tags->attributes()->css)?$tags->attributes()->css:"")*/."' 
					class='".
					((isset($tag_attrs["display"])&&$tag_attrs["display"]!="design")?$game_type_name."_".$game_struct_element."_display ":"").
					"record_wrapper ".$game_struct_element."_wrapper'>";
				echo "<div class='horizontal_title_wrapper ".$game_type_name."_".$game_struct_element."_css '>";
				
				if (!$design_row) {
					/*Top cím elhelyezése*/
					if (isset($tag_attrs["top_title"]))
						echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_top_title'>".$xml_lang_conf[$game_struct_element.".top_title"]."</div>";
						
					echo "<div class='"
						.$game_type_name."_".$game_struct_element."_title_padding ".
						((isset($tag_attrs["left_title"]))?"base_left_title_padding ":"").
						((isset($tag_attrs["right_title"]))?"base_right_title_padding ":"").
						"vertical_title_wrapper'>";
					
					/*horizontális címek elhelyezése*/
					if (isset($tag_attrs["left_title"]))
						echo "<div class='vertical_left_title ".$game_type_name."_".$game_struct_element."_left_title'>".$xml_lang_conf[$game_struct_element.".left_title"]."</div>";
					if (isset($tag_attrs["right_title"]))
						echo "<div class='vertical_right_title ".$game_type_name."_".$game_struct_element."_right_title'>".$xml_lang_conf[$game_struct_element.".right_title"]."</div>";
			
					//Ha nincs limit: insert-form
					if ($edit_mode == true && $game_struct_limit == 0) {
						$insert_id = "insertform_".$game_struct_element;
						
						echo "<form id=\"".$insert_id."\" class=\"insertform\">";
						echo "<input type=\"hidden\" name=\"specID\" value=\"".$game_db_element["specID"]."\" />";
						echo "<input type=\"hidden\" name=\"dbname\" value=\"s_".$game_type."_".$game_struct_element."\" />";
						
						echo "<div class=\"record_insert\" onclick=\"ajax_insert('".$insert_id."', '".$game_type."_".$game_struct_element."');\">up</div>";
						
						$record_id = -1;
						foreach ($tags["record"] as $node_name => $records) {
							if (strpos($node_name, "_attr")) continue;
							if (1/*$node_name == "record"*/) {
								$record_id++;
								$node_attrs = $tags["record"][$node_name."_attr"];
								$record_class = $game_type_name."_".$game_struct_element."_record_".$record_id."_css";
								
								echo "<div class='horizontal_title_wrapper to_block'>";
									/*Top cím elhelyezése*/
									if (isset($node_attrs["top_title"]))
										echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_top_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".top_title"]."</div>";
											
								echo "<div class='"
									.$game_type_name."_".$game_struct_element."_record_".$record_id."_title_padding ".
									((isset($node_attrs["left_title"]))?"base_left_title_padding ":"").
									((isset($node_attrs["right_title"]))?"base_right_title_padding ":"").
									"vertical_title_wrapper'>";
									
								/*horizontális címek elhelyezése*/
								if (isset($node_attrs["left_title"]))
									echo "<div class='vertical_left_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_left_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".left_title"]."</div>";
								if (isset($node_attrs["right_title"]))
									echo "<div class='vertical_right_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_right_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".right_title"]."</div>";
								
								echo "<div class='char_container $record_class'>";
									if (isset($node_attrs["title"])) {
										if ($node_attrs["title"] != "")
											$title = $node_attrs["title"];
										else
											$title = $node_name;
										echo "<div class=\"field\" class=\"".$game_struct_element."_title_".$record_id."\">".$node_attrs["title"]."</div>";
									}
									$field_id = 0;
									foreach ($records["field"] as $field_name => $field) {
										if (strpos($field_name, "_attr")) continue;
										$field_attrs = $records["field"][$field_name."_attr"];
										if (isset($field_attrs["static_content"]) && strval($field_attrs["switchable"]) == "false") continue;
										if (isset($field_attrs["placeholder"]))
											$placeholder = $field_attrs["placeholder"];
										else
											$placeholder = $field_attrs["name"];
											
										if (empty($xml_lang_conf[$field_attrs["name"]]))
											$content = $field_attrs["name"];
										else
											$content = $xml_lang_conf[$field_attrs["name"]];
											
										$field_class = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_css";
										$field_label_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_label_css";
										$field_input_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_input_css";
										$field_visibility_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_visibility_css";
										$field_id++;
										echo "<div class='field $field_class'><div class='label $field_label_css'>";
										if (strval($field_attrs["switchable"]) != "false")
											echo "<input class='$field_visibility_css' type='checkbox' name='vis_".$field_attrs["name"]."' value='1' ".((strval($field_attrs["visible"]) != "false")?" checked":"")."/>";
										else
											echo "<input type=\"hidden\" name=\"vis_".$field_attrs["name"]."\" value=\"".((strval($field_attrs["visible"]) != "false")?0:1)."\" />";
										
										echo $content."</div>";
										if (!isset($field_attrs["static_content"]))
											echo "<input class='$field_input_css' type=\"text\" placeholder=\"".$placeholder."\" name=\"".$field_attrs["name"]."\" />";
										else
											echo $field_attrs["static_content"];
											
										echo "</div>";
									}
								echo "</div>";
								
								echo "</div>";
									/*Bot cím elhelyezése*/
									if (isset($node_attrs["bot_title"]))
										echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_bot_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".bot_title"]."</div>";
										
								echo "</div>";
								
							}
						}
						echo "</form>";
					}
					//to Copy
					if ($game_struct_limit == 0 && !$viewer_mode) {
						echo "<form class='to_copy' id='' class='insertform'>";
						if ($edit_mode) echo "<div class='record_delete'>del</div>";
						if ($edit_mode) echo "<input type='hidden' name='dbname' value='s_".$game_type."_".$game_struct_element."' />";
						echo "<input type='hidden' name='specID' value='' />";
						$record_id = -1;
						foreach ($tags["record"] as $node_name => $records) {
							if (strpos($node_name, "_attr")) continue;
							if (1/*$node_name == "record"*/) {
								$record_id++;
								$node_attrs = $tags["record"][$node_name."_attr"];
								
								
								/*TITLE*/
								echo "<div class='horizontal_title_wrapper to_block'>";
									/*Top cím elhelyezése*/
									if (isset($node_attrs["top_title"]))
										echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_top_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".top_title"]."</div>";
											
								echo "<div class='"
									.$game_type_name."_".$game_struct_element."_record_".$record_id."_title_padding ".
									((isset($node_attrs["left_title"]))?"base_left_title_padding ":"").
									((isset($node_attrs["right_title"]))?"base_right_title_padding ":"").
									"vertical_title_wrapper'>";
									
								/*horizontális címek elhelyezése*/
								if (isset($node_attrs["left_title"]))
									echo "<div class='vertical_left_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_left_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".left_title"]."</div>";
								if (isset($node_attrs["right_title"]))
									echo "<div class='vertical_right_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_right_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".right_title"]."</div>";
								
								/*TITLE*/
								
								
								echo "<div class=\"char_container ".$game_type_name."_".$game_struct_element."_record_".$record_id."_css\">";
									$field_id = 0;
									foreach ($records["field"] as $field_name => $field) {
										if (strpos($field_name, "_attr")) continue;
										$field_attrs = $records["field"][$field_name."_attr"];
										if (isset($field_attrs["placeholder"]))
											$placeholder = $field_attrs["placeholder"];
										else
											$placeholder = $field_attrs["name"];
											
										if (empty($xml_lang_conf[$field_attrs["name"]]))
											$content = $field_attrs["name"];
										else
											$content = $xml_lang_conf[$field_attrs["name"]];
											
										$field_class = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_css";
										$field_label_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_label_css";
										$field_input_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_input_css";
										$field_visibility_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_visibility_css";
										$field_id++;
										echo "<div class='field $field_class'><div class='label $field_label_css'>";
										if (strval($field_attrs["switchable"]) != "false")
											echo "<input class='$field_visibility_css' type=\"checkbox\" name=\"vis_".$field_attrs["name"]."\" value=\"1\" ".((strval("vis_".$field_attrs["visible"]) != "false")?" checked":"")."/>";
										else
											echo "<input type=\"hidden\" name=\"vis_".$field_attrs["name"]."\" value=\"".((strval("vis_".$field_attrs["visible"]) != "false")?0:1)."\" />";
										
										echo $content."</div>";
										if (!isset($field_attrs["static_content"]))
											echo "<input class='$field_input_css' type=\"text\" placeholder=\"".$placeholder."\" name=\"".$field_attrs["name"]."\" value=\"\" />";
										else
											echo $field_attrs["static_content"];
											
										echo "</div>";
									}
								echo "</div>";
								
								echo "</div>";
									/*Bot cím elhelyezése*/
									if (isset($node_attrs["bot_title"]))
										echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_bot_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".bot_title"]."</div>";
										
								echo "</div>";
							}
							else if ($node_name == "break") echo "<br clear=\"all\" />";
						}
						echo "</form>";
					}
						while ( ((!$viewer_mode)?($game_db_element = mysql_fetch_assoc($result)):true) ) {
							$form_spec_id = strval($game_type."_".$game_struct_element."_".$game_db_element["specID"]);
							echo "<form id='$form_spec_id' class='form_check_upload".(($game_struct_limit == 0)?" insertform":"")."'>";
							if ($edit_mode) {
							$first_rule = true;
							?>
							<script type="text/javascript">
							/*
								$(function(e) {
									$("#<?php echo $form_spec_id; ?>").validate({
										rules: {
											<?php
												foreach ($tags as $node_name => $records) {
													if (strpos($node_name, "_attr")) continue;
													foreach ($records["field"] as $field_name => $field) {
														if (strpos($field_name, "_attr")) continue;
														$field_attrs = $records["field"][$field_name."_attr"];
														
														$rule_added = false;
														unset($rules_length);
														$rules_length = explode(",", strval($field_attrs["length"]));
														$rules_type = strval($field_attrs["type"]);
														
														unset($added_rules);
														if (isset($field_attrs["rules"])) {
															$added_rules = strval($field_attrs["rules"]);
															$added_rules = explode(",", $added_rules);
														}
														//echo count($added_rules);
														if (count($rules_length) == 2 || (isset($field_attrs["rules"]) && count($added_rules) > 0)) {
															echo (($first_rule)?"":", ").$field_attrs["name"].": {";
															if (count($rules_length) == 2) {
																if($rules_type == "number") {
																	echo "min: ".$rules_length[0].", max: ".$rules_length[1];
																} else {
																	echo "minlength: ".$rules_length[0].", maxlength: ".$rules_length[1];
																}
																$rule_added = true; $first_rule = false;
															}
															//print_r($added_rules);
															if (count($added_rules) > 0) {
																foreach ($added_rules as $rule) {
																	echo (($rule_added)?", ":"").$rule.": true";
																	$rule_added = true; $first_rule = false;
																}
															}
															echo "}";
														}
													}
												}
											?>
										}
									});
								});
								*/
							</script>
							<?php
							}
							if ($edit_mode && $game_struct_limit == 0) echo "<div class=\"record_delete\">del</div>";
							if ($edit_mode) echo "<input type=\"hidden\" name=\"dbname\" value=\"s_".$game_type."_".$game_struct_element."\" />";
							echo "<input type=\"hidden\" name=\"specID\" value=\"".$game_db_element["specID"]."\" />";
							$record_id = -1;
							foreach ($tags["record"] as $node_name => $records) {
								if (strpos($node_name, "_attr")) continue;
								if (1/*$node_name == "record"*/) {
									$record_id++;
									$node_attrs = $tags["record"][$node_name."_attr"];
									$title_on = false; $title_padding = ""; $title_css = "";
									
									
									/*TITLE*/
									echo "<div class='horizontal_title_wrapper to_block'>";
										/*Top cím elhelyezése*/
										if (isset($node_attrs["top_title"]))
											echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_top_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".top_title"]."</div>";
												
									echo "<div class='"
										.$game_type_name."_".$game_struct_element."_record_".$record_id."_title_padding ".
										((isset($node_attrs["left_title"]))?"base_left_title_padding ":"").
										((isset($node_attrs["right_title"]))?"base_right_title_padding ":"").
										"vertical_title_wrapper'>";
										
									/*horizontális címek elhelyezése*/
									if (isset($node_attrs["left_title"]))
										echo "<div class='vertical_left_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_left_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".left_title"]."</div>";
									if (isset($node_attrs["right_title"]))
										echo "<div class='vertical_right_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_right_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".right_title"]."</div>";
									
									/*TITLE*/
									
									
									echo "<div class=\"char_container ".$game_type_name."_".$game_struct_element."_record_".$record_id."_css\" style=\"".$title_padding."\">";
										//if ($title_on) echo "<div class=\"field in_title_".$title_align." ".$game_struct_element."_title_".$record_id."\" style=\"".$title_css."\">".$title_content."</div>";
										$field_id = 0;
										foreach ($records["field"] as $field_name => $field) {
											if (strpos($field_name, "_attr")) continue;
											$field_attrs = $records["field"][$field_name."_attr"];
											if (isset($field_attrs["placeholder"]))
												$placeholder = $field_attrs["placeholder"];
											else
												$placeholder = $field_attrs["name"];
												
											if (empty($xml_lang_conf[$field_attrs["name"]]))
												$content = $field_attrs["name"];
											else
												$content = $xml_lang_conf[$field_attrs["name"]];
												
											$field_class = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_css";
											$field_label_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_label_css";
											$field_input_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_input_css";
											$field_visibility_css = $game_type_name."_".$game_struct_element."_field_".$record_id."_".$field_id."_visibility_css";
											$field_id++;
											echo "<div class='field $field_class'><div class='label $field_label_css'>";
											if ($edit_mode && strval($field_attrs["switchable"]) != "false")
												echo "<input class='$field_visibility_css' type=\"checkbox\" name=\"vis_".$field_attrs["name"]."\" value=\"1\" ".(($game_db_element[strval("vis_".$field_attrs["name"])] == 1)?" checked":"")."/>";
											else
												echo "<input type=\"hidden\" name=\"vis_".$field_attrs["name"]."\" value=\"".((strval("vis_".$field_attrs["visible"]) != "false")?0:1)."\" />";
											
											if (isset($field_attrs["static_content"])) echo "<input type=\"hidden\" name=\"stat_".$field_attrs["name"]."\" value=\"\" />";
											echo $content."</div>";
												if ($edit_mode && !isset($field_attrs["static_content"]))
													echo "<input class='$field_input_css' type=\"text\" name=\"".$field_attrs["name"]."\" placeholder=\"".$placeholder."\" value=\"".$game_db_element[strval($field_attrs["name"])]."\" />";
												else {
													if ($game_db_element["vis_".strval($field_attrs["name"])] == 1 && !isset($field_attrs["static_content"]))
														echo $game_db_element[strval($field_attrs["name"])];
													else if ($game_db_element["vis_".strval($field_attrs["name"])] == 1 && isset($field_attrs["static_content"]))
														echo $field_attrs["static_content"];
													else if ($game_db_element["vis_".strval($field_attrs["name"])] != 1 && $edit_mode == false)
														echo $base_config["charfield_invisible"];
												}
											echo "</div>";
										}
									echo "</div>";
									
									echo "</div>";
										/*Bot cím elhelyezése*/
										if (isset($node_attrs["bot_title"]))
											echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_record_".$record_id."_bot_title'>".$xml_lang_conf[$game_struct_element.".record_".$record_id.".bot_title"]."</div>";
											
									echo "</div>";
								}
							}
							echo "</form>";
							if ($viewer_mode) break;
						}
					
					echo "</div>";
					
					/*Bot cím elhelyezése*/
					if (isset($tag_attrs["bot_title"]))
						echo "<div class='horizontal_title ".$game_type_name."_".$game_struct_element."_bot_title'>".$xml_lang_conf[$game_struct_element.".bot_title"]."</div>";
				} else echo $xml_lang_conf[$game_struct_element]; /*Ha díszítősor*/
				echo "</div>";
				echo "</div>";
			}
			echo "</div>";
			if (!$viewer_mode) {
			?>
			<script type="text/javascript">
				add_update_checker(".form_check_upload");
				/*
				$(function(){
					$('input[type=checkbox]').prettyCheckboxes();
				});
				*/
			</script>
			<?php
			}
			if ($viewer_mode) echo "</body></html>";
		} else echo "1"; //Hiányzó jelszó
	$db->stop();
?>