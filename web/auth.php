<?php
	/**
		@author: Hubert Viktor
		@since: 2012
		Ellenőrzi, hogy a felhasználó bejelentkezett-e.
		Ezzel ki lehet védeni az olyan ügyeskedéseket, hogyha valaki megjegyzi pl:
		http://rpg.hv-web.hu/main/ címet. Hiába írja be, visszadobjuk a loginhoz.
	*/
	
	session_start();
		if(!isset($_SESSION['user_id']) || (trim($_SESSION['user_id']) == '')) {
			$MSG = 'Nem vagy bejelentkezve!';
			header("location: /rpg/");
			exit();
		}
	session_write_close();
?>