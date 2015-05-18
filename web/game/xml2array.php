<?php

/*Deprecated*/
function record_correction($arr){
	return correction($arr);
}
/*Javítás a tömbátalakításhoz, mivel nem teljesen következetes az egyelemű részekkel.*/
function correction($arr){
	//return $arr;
	//echo "\n";
	foreach ($arr as $mainkey => $maintag) {
		if (strpos($mainkey,"_attr")) continue;
		if ($arr[$mainkey."_attr"]["display"]=="design") continue;
		foreach ($maintag as $key => $rtag) {
			/*Rekord korrekció*/
			if (!isset($arr[$mainkey][$key][0])) {
				
				$record_move = $arr[$mainkey]["record"];
				$record_attr_move = $arr[$mainkey]["record_attr"];
				
				unset($arr[$mainkey]["record"]);
				unset($arr[$mainkey]["record_attr"]);
				$arr[$mainkey]["record"][0] = $record_move;
				$arr[$mainkey]["record"]["0_attr"] = $record_attr_move;
			}
			
			/*Field korrekció*/
			foreach ($arr[$mainkey]["record"] as $field_key => $field_data) {
				if (!isset($arr[$mainkey]["record"][$field_key]["field"][0])) {
					
					$field_move = $arr[$mainkey]["record"][$field_key]["field"];
					$field_attr_move = $arr[$mainkey]["record"][$field_key]["field_attr"];
					
					unset($arr[$mainkey]["record"][$field_key]["field"]);
					unset($arr[$mainkey]["record"][$field_key]["field_attr"]);
					$arr[$mainkey]["record"][$field_key]["field"][0] = $field_move;
					$arr[$mainkey]["record"][$field_key]["field"]["0_attr"] = $field_attr_move;
				}
			}
			
			break;
		}
	}
	//echo "\n";
	//echo "\n";
	return $arr;
}

function xml2array($url, $get_attributes = 1, $priority = 'tag')
	{
		$contents = "";
		if (!function_exists('xml_parser_create'))
		{
			return array ();
		}
		$parser = xml_parser_create('');
		if (!($fp = @ fopen($url, 'rb')))
		{
			return array ();
		}
		while (!feof($fp))
		{
			$contents .= fread($fp, 8192);
		}
		fclose($fp);
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
		if (!$xml_values)
			return; //Hmm...
		$xml_array = array ();
		$parents = array ();
		$opened_tags = array ();
		$arr = array ();
		$current = & $xml_array;
		$repeated_tag_index = array ();
		foreach ($xml_values as $data)
		{
			unset ($attributes, $value);
			extract($data);
			$result = array ();
			$attributes_data = array ();
			if (isset ($value))
			{
				if ($priority == 'tag')
					$result = $value;
				else
					$result['value'] = $value;
			}
			if (isset ($attributes) and $get_attributes)
			{
				foreach ($attributes as $attr => $val)
				{
					if ($priority == 'tag')
						$attributes_data[$attr] = $val;
					else
						$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}
			if ($type == "open")
			{
				$parent[$level -1] = & $current;
				if (!is_array($current) or (!in_array($tag, array_keys($current))))
				{
					$current[$tag] = $result;
					if ($attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					$current = & $current[$tag];
				}
				else
				{
					if (isset ($current[$tag][0]))
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						
						/*Javítás*/
						if($attributes_data) $current[$tag][$repeated_tag_index[$tag.'_'.$level].'_attr'] = $attributes_data;
						/*Javítás*/
						
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else
					{
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 2;
						if (isset ($current[$tag . '_attr']))
						{
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset ($current[$tag . '_attr']);
						}
						if($attributes_data) $current[$tag]['1_attr'] = $attributes_data;
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current = & $current[$tag][$last_item_index];
				}
			}
			elseif ($type == "complete")
			{
				if (!isset ($current[$tag]))
				{
					$current[$tag] = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' and $attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
				}
				else
				{
					if (isset ($current[$tag][0]) and is_array($current[$tag]))
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						if ($priority == 'tag' and $get_attributes and $attributes_data)
						{
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else
					{
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if ($priority == 'tag' and $get_attributes)
						{
							if (isset ($current[$tag . '_attr']))
							{
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset ($current[$tag . '_attr']);
							}
							if ($attributes_data)
							{
								$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
					}
				}
			}
			elseif ($type == 'close')
			{
				$current = & $parent[$level -1];
			}
		}
		return ($xml_array);
	}
?>