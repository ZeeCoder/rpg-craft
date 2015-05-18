
<?php
	/*
		=============================================================
		RPGCraft ENGINE
		Készítette: Hubert Viktor
		Minden jog fenntartva.
		=============================================================
	
		css_from_xml.php
			A karakterlaphoz tartozó stíluslapot generálja le a megadott attribútumok alapján.
		
		Paraméterek
			- game_type: kötelező. A karakterlap, amiből a stíluslapot generálja.
			- more_info: opcionális. Ha adott, kiírja a legenerált stíluslapot.
			- struct: opcionális. Ha adott, kiírja az xml-ből kapott tömböt is.
			
		Visszatérési érték:
			0/1 attól függően sikeres volt-e a generálás.
			0: sikeres, 1: sikertelen.
	*/

	header("Content-Type: text/plain");
	require_once("../game/xml2array.php");
	
	if (isset($_GET["more_info"])) $more_info=true;
	else $more_info=false;
	if (isset($_GET["struct"])) $struct=true;
	else $struct=false;

	if (isset($_GET["game_type"])) {
		$game_type = $_GET["game_type"];
		$ch_arr = xml2array("../charsheet_xml/".$game_type."/".$game_type.".xml");
		$ch_attr = $ch_arr["charsheet_attr"];
		
		/*A sima rekordok nem tömbben vannak, ezért íródott ez a függvény*/
		$ch_arr["charsheet"] = correction($ch_arr["charsheet"]);
		
		/*
		echo "charsheet\n";
		print_r($ch_arr);
		*/
		
		
		foreach ($ch_arr["charsheet"] as $tagname => $tags) {
			if ($tagname == "break") continue;
			if (strpos($tagname, "_attr")) continue;
			/*
			$record_array = key(reset($tags))=="0"||(isset($ch_arr["charsheet"][$tagname."_attr"]["display"]) && $ch_arr["charsheet"][$tagname."_attr"]["display"]=="design");
			//echo $record_array."-".$tagname."\n";
			
			if (!$record_array) {
				//print_r($tags);
				
				$rec = array_shift($ch_arr["charsheet"][$tagname]);
				$rec_attr = array_shift($ch_arr["charsheet"][$tagname]);
				//print_r($rec);
				//print_r($rec_attr);
				
				$ch_arr["charsheet"][$tagname]["record"]["0"] = $rec;
				$ch_arr["charsheet"][$tagname]["record"]["0_attr"] = $rec_attr;
				
				//$tags["record"]["0"] = $rec;
				//$tags["record"]["0_attr"] = $rec_attr;
				
				//print_r($ch_arr["charsheet"][$tagname]);
				//echo "charsheet\n";
			}
			*/
		}
		
		$style=array();
		
		/*Fő tagek feldolgozása*/
		foreach ($ch_arr["charsheet"] as $tagname => $tags) {
			if ($tagname == "break") continue;
			if (strpos($tagname, "_attr")) continue;
			$tag_attrs = $ch_arr["charsheet"][$tagname."_attr"]; /*tag attribútumai*/
			
			/*
			echo "<pre>$tagname\n";
			print_r($tag_attrs);
			echo "</pre>";
			*/
			
			/*Fő tag title stílusai*/
				if (isset($tag_attrs["left_title_css"])) $style[$tagname."_left_title"] = $tag_attrs["left_title_css"];
					if (isset($tag_attrs["left_title_hover_css"])) $style[$tagname."_left_title_hover"] = $tag_attrs["left_title_hover_css"];
				if (isset($tag_attrs["right_title_css"])) $style[$tagname."_right_title"] = $tag_attrs["right_title_css"];
					if (isset($tag_attrs["right_title_hover_css"])) $style[$tagname."_right_title_hover"] = $tag_attrs["right_title_hover_css"];
				if (isset($tag_attrs["top_title_css"])) $style[$tagname."_top_title"] = $tag_attrs["top_title_css"];
					if (isset($tag_attrs["top_title_hover_css"])) $style[$tagname."_top_title_hover"] = $tag_attrs["top_title_hover_css"];
				if (isset($tag_attrs["bot_title_css"])) $style[$tagname."_bot_title"] = $tag_attrs["bot_title_css"];
					if (isset($tag_attrs["bot_title_hover_css"])) $style[$tagname."_bot_title_hover"] = $tag_attrs["bot_title_hover_css"];
				/*Fő tag title dimenziók stílusai*/
				if (isset($tag_attrs["left_title_width"])) $style[$tagname."_left_title"] .= "width: ".$tag_attrs["left_title_width"]."px;";
					if (isset($tag_attrs["left_title_width"])) $style[$tagname."_title_padding"] .= "padding-left: ".$tag_attrs["left_title_width"]."px;"; /*Korrigálás padding-gal*/
				if (isset($tag_attrs["right_title_width"])) $style[$tagname."_right_title"] .= "width: ".$tag_attrs["right_title_width"]."px;";
					if (isset($tag_attrs["right_title_width"])) $style[$tagname."_title_padding"] .= "padding-right: ".$tag_attrs["right_title_width"]."px;"; /*Korrigálás padding-gal*/
				if (isset($tag_attrs["top_title_height"])) $style[$tagname."_top_title"] .= "height: ".$tag_attrs["top_title_height"]."px;";
				if (isset($tag_attrs["bot_title_height"])) $style[$tagname."_bot_title"] .= "height: ".$tag_attrs["bot_title_height"]."px;";
			
			/*Fő tag stílusa*/
			if (isset($tag_attrs["css"])) $style[$tagname."_css"] = $tag_attrs["css"];
			if (isset($tag_attrs["hover_css"])) $style[$tagname."_hover_css"] = $tag_attrs["hover_css"];
			
			/*Fő tag display stílusa*/
			if (isset($tag_attrs["display"])&&$tag_attrs["display"]!="design") {
				if ($tag_attrs["display"]=="left") $display_type="text-align: left;";
				else if ($tag_attrs["display"]=="right") $display_type="text-align: right;";
				else if ($tag_attrs["display"]=="center") $display_type="text-align: center;";
				else if ($tag_attrs["display"]=="inline") $display_type="display: inline-block;";
				$style[$tagname."_display"] = $display_type;
			}
			
			/*Leszármazó stílusok*/
				/*Általános*/
				$tag_lower_css = "";
				$tag_lower_hover_css = "";
				if (isset($tag_attrs["lower_css"])) $tag_lower_css .= $tag_attrs["lower_css"];
				if (isset($tag_attrs["lower_hover_css"])) $tag_lower_hover_css .= $tag_attrs["lower_hover_css"];
				/*Label*/
				$tag_lower_label_css = "";
				$tag_lower_label_hover_css = "";
				if (isset($tag_attrs["lower_label_css"])) $tag_lower_label_css .= $tag_attrs["lower_label_css"];
				if (isset($tag_attrs["lower_label_hover_css"])) $tag_lower_label_hover_css .= $tag_attrs["lower_label_hover_css"];
				/*input*/
				$tag_lower_input_css = "";
				$tag_lower_input_hover_css = "";
				if (isset($tag_attrs["lower_input_css"])) $tag_lower_input_css .= $tag_attrs["lower_input_css"];
				if (isset($tag_attrs["lower_input_hover_css"])) $tag_lower_input_hover_css .= $tag_attrs["lower_input_hover_css"];
			
			/*Rekordok feldolgozása*/
			$record_count=-1;
			foreach ($tags["record"] as $node_name => $records) {
				$lower_css = $tag_lower_css;
				$lower_hover_css = $tag_lower_hover_css;
				$lower_label_css = $tag_lower_label_css;
				$lower_label_hover_css = $tag_lower_label_hover_css;
				$lower_input_css = $tag_lower_input_css;
				$lower_input_hover_css = $tag_lower_input_hover_css;
				//echo "\n".$tagname."-".$node_name."\n";
				if ($node_name == "break" && $node_name != 0) continue;
				if (strpos($node_name, "_attr")) continue;
				//echo "\n".$tagname."-".$node_name." - rekord!\n";
				$record_count++;
				$node_attrs = $tags["record"][$node_name."_attr"];
				//echo "attr: ";print_r($node_attrs);
				
				/*Record tag stílusa*/
				if (isset($node_attrs["css"])) $style[$tagname."_record_".$record_count."_css"] = $node_attrs["css"];
				if (isset($node_attrs["hover_css"])) $style[$tagname."_record_".$record_count."_hover_css"] = $node_attrs["hover_css"];
			
				/*Record tag title stílusai*/
					if (isset($node_attrs["left_title_css"])) $style[$tagname."_record_".$record_count."_left_title"] = $node_attrs["left_title_css"];
						if (isset($node_attrs["left_title_hover_css"])) $style[$tagname."_record_".$record_count."_left_title_hover"] = $node_attrs["left_title_hover_css"];
					if (isset($node_attrs["right_title_css"])) $style[$tagname."_record_".$record_count."_right_title"] = $node_attrs["right_title_css"];
						if (isset($node_attrs["right_title_hover_css"])) $style[$tagname."_record_".$record_count."_right_title_hover"] = $node_attrs["right_title_hover_css"];
					if (isset($node_attrs["top_title_css"])) $style[$tagname."_record_".$record_count."_top_title"] = $node_attrs["top_title_css"];
						if (isset($node_attrs["top_title_hover_css"])) $style[$tagname."_record_".$record_count."_top_title_hover"] = $node_attrs["top_title_hover_css"];
					if (isset($node_attrs["bot_title_css"])) $style[$tagname."_record_".$record_count."_bot_title"] = $node_attrs["bot_title_css"];
						if (isset($node_attrs["bot_title_hover_css"])) $style[$tagname."_record_".$record_count."_bot_title_hover"] = $node_attrs["bot_title_hover_css"];
					/*Record tag title dimenziók stílusai*/
					if (isset($node_attrs["left_title_width"])) $style[$tagname."_record_".$record_count."_left_title"] .= "width: ".$node_attrs["left_title_width"]."px;";
						if (isset($node_attrs["left_title_width"])) $style[$tagname."_record_".$record_count."_title_padding"] .= "padding-left: ".$node_attrs["left_title_width"]."px;"; /*Korrigálás padding-gal*/
					if (isset($node_attrs["right_title_width"])) $style[$tagname."_record_".$record_count."_right_title"] .= "width: ".$node_attrs["right_title_width"]."px;";
						if (isset($node_attrs["right_title_width"])) $style[$tagname."_record_".$record_count."_title_padding"] .= "padding-right: ".$node_attrs["right_title_width"]."px;"; /*Korrigálás padding-gal*/
					if (isset($node_attrs["top_title_height"])) $style[$tagname."_record_".$record_count."_top_title"] .= "height: ".$node_attrs["top_title_height"]."px;";
					if (isset($node_attrs["bot_title_height"])) $style[$tagname."_record_".$record_count."_bot_title"] .= "height: ".$node_attrs["bot_title_height"]."px;";
				
				/*Leszármazó stílusok*/
					/*Általános*/
					if (isset($node_attrs["lower_css"])) $lower_css .= $node_attrs["lower_css"];
					if (isset($node_attrs["lower_hover_css"])) $lower_hover_css .= $node_attrs["lower_hover_css"];
					/*Label*/
					if (isset($node_attrs["lower_label_css"])) $lower_label_css .= $node_attrs["lower_label_css"];
					if (isset($node_attrs["lower_label_hover_css"])) $lower_label_hover_css .= $node_attrs["lower_label_hover_css"];
					/*input*/
					if (isset($node_attrs["lower_input_css"])) $lower_input_css .= $node_attrs["lower_input_css"];
					if (isset($node_attrs["lower_input_hover_css"])) $lower_input_hover_css .= $node_attrs["lower_input_hover_css"];
					
				/*Mezők feldolgozása*/
				$field_count=-1;
				foreach ($records["field"] as $field_name => $field) {
					if (strpos($field_name, "_attr")) continue;
					$field_count++;
					$field_attrs = $records["field"][$field_name."_attr"];
					
					/*Field tag stílusa*/
						/*normál*/
						$class = $tagname."_field_".$record_count."_".$field_count."_css";
						if (!empty($lower_css)) $style[$class] = $lower_css;
						if (isset($field_attrs["css"])) $style[$class] .= $field_attrs["css"];
						/*hover*/
						$class = $tagname."_field_".$record_count."_".$field_count."_hover_css";
						if (!empty($lower_hover_css)) $style[$class] = $lower_hover_css;
						if (isset($field_attrs["hover_css"])) $style[$class] .= $field_attrs["hover_css"];
						
					/*Field tag label stílusa*/
						/*normál*/
						$class = $tagname."_field_".$record_count."_".$field_count."_label_css";
						if (!empty($lower_label_css)) $style[$class] = $lower_label_css;
						if (isset($field_attrs["label_css"])) $style[$class] .= $field_attrs["label_css"];
						/*hover*/
						$class = $tagname."_field_".$record_count."_".$field_count."_label_hover_css";
						if (!empty($lower_label_hover_css)) $style[$class] = $lower_label_hover_css;
						if (isset($field_attrs["label_hover_css"])) $style[$class] .= $field_attrs["label_hover_css"];
					
					/*Field tag input stílusa*/
						/*normál*/
						$class = $tagname."_field_".$record_count."_".$field_count."_input_css";
						if (!empty($lower_input_css)) $style[$class] = $lower_input_css;
						if (isset($field_attrs["input_css"])) $style[$class] .= $field_attrs["input_css"];
						/*hover*/
						$class = $tagname."_field_".$record_count."_".$field_count."_input_hover_css";
						if (!empty($lower_input_hover_css)) $style[$class] = $lower_input_hover_css;
						if (isset($field_attrs["input_hover_css"])) $style[$class] .= $field_attrs["input_hover_css"];
					
					/*Field tag visibility stílusa*/
					if (isset($field_attrs["visibility_css"])) $style[$tagname."_field_".$record_count."_".$field_count."_visibility_css"] = $field_attrs["visibility_css"];
					if (isset($field_attrs["visibility_hover_css"])) $style[$tagname."_field_".$record_count."_".$field_count."_visibility_hover_css"] = $field_attrs["visibility_hover_css"];
				}
			}
		}
		$style_def = "";
		/*
		if ($more_info) {
			echo "style\n";
			print_r($style);
		}
		*/
		
		foreach ($style as $key => $def) {
			$style_def .= ".".$game_type."_".((strpos($key, "hover")?(str_replace("_hover","",$key).":hover"):$key))." {".str_replace(";"," !important;",$def)."}\n";
		}
		$f = fopen($game_type."/".$game_type.".css", "w");
		$ret = ((fwrite($f, $style_def))?0:1);
		fclose($f);
		if ($more_info&&$ret!=1) echo $style_def;
		else if ($struct&&$ret!=1) print_r($ch_arr);
		else echo $ret;
		
	} else echo 1;
	
?>