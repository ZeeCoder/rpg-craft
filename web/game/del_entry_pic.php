<?php
	session_start();
		if (isset($_SESSION["act_user_is_gm"]))
			$sess_act_user_is_gm = $_SESSION["act_user_is_gm"];
	session_write_close();

	try {
		if (!(  isset($sess_act_user_is_gm) && isset($_POST["src"]) && isset($_POST["tumbsrc"])  ))
			throw new Exception("1");
		if (!(  is_file($_POST["src"]) && is_file($_POST["tumbsrc"])  ))
			throw new Exception("2");
		if (!(  unlink($_POST["src"])&&unlink($_POST["tumbsrc"])  ))
			throw new Exception("3");
		
		echo 0;
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>