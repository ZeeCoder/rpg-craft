<?php
	session_start();
		$lang = $_SESSION['lang'];
		$root = $_SESSION['PAGE_ROOT'];
		$_SESSION = array();
		$_SESSION['lang'] = $lang;
		$_SESSION['PAGE_ROOT'] = $root;
	session_write_close();
	header( 'Location: '.$root.$lang );
?>