<?php
	if (isset($_GET["game_type"])) {
		$game_type = $_GET["game_type"];
		$file = "../charsheet_xml/".$_GET["game_type"]."/".$_GET["game_type"].".xml";
		$folder = "../charsheet_xml/".$_GET["game_type"]."/";
		$msg .= is_file($file).".";
		if (is_file($file))
			$msg .= unlink($file).".";
		$msg .= is_dir($folder).".";
		if (is_dir($folder))
			$msg .= rmdir($folder).".";
		ini_set("error_log", $_SERVER['DOCUMENT_ROOT']."/rpg/logs/asdasd.log");
		error_log($file."--".$msg."--");
	}
?>