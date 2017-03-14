<?php
	session_start();
		$sess_user_rights = @$_SESSION['user_rights'];
	session_write_close();
	
	
	$configini = parse_ini_file('config.ini', true);
	

	$hv_template_config = array(
		'config' => 'config.ini'
		,'session_start' => true
		,'favicon' => 'favicon.ico'
		,'css_list' => array(
			/*'css/normalize.css'
			,*/
			'css/style.css'
			,'css/main.css'
			,'plugins/hv_alert/hv_alert.css'
			,'plugins/hv_ajax_loader/hv_ajax_loader.css'
		)
		,'js_header_list' => array(
			'js/jquery.min.js'
		)
		,'js_list' => array(
			'plugins/hv_ajax_loader/hv_ajax_loader.js'
			,'plugins/jquery.cookie.js'
			,'plugins/jquery.validate.js'
			,'js/main.js'
			,'plugins/hv_alert/hv_alert.js'
		)
		,'root' => $configini['root']
		,'rootObj' => new HVRouting( array(
				'root' => $configini['root']
				,'def_lang' => 'hu'
				,'supported_langs' => 'hu,en'
			) )
		,'default_page' => 'news'
		,'doctype' => 'strict'
		
		,'def_title' => 'RPGCraft'
		,'def_desc' => ''
		,'user_right' => $sess_user_rights
		,'pages' => array(
			'news' => array(
				'hu' => array(
					'name' => 'hirek'
					,'full_name' => 'Hírek'
					,'title_suffix' => ' | Hírek'
					,'desc' => 'RPGCraft: Hírek.'
				)
				,'en' => array(
					'name' => 'news'
					,'full_name' => 'News'
					,'title_suffix' => ' | News'
					,'desc' => 'RPGCraft: News.'
				)
			)
			,'friends' => array(
				'hu' => array(
					'name' => 'baratok'
					,'full_name' => 'Barátok'
					,'title_suffix' => ' | Barátok'
					,'desc' => 'RPGCraft: Barátok.'
					,'needed_right' => 1
				)
				,'en' => array(
					'name' => 'friends'
					,'full_name' => 'Friends'
					,'title_suffix' => ' | Friends'
					,'desc' => 'RPGCraft: Friends.'
					,'needed_right' => 1
				)
			)
			,'games' => array(
				'hu' => array(
					'name' => 'jatekok'
					,'full_name' => 'Játékok'
					,'title_suffix' => ' | Játékok'
					,'desc' => 'RPGCraft: Játékok.'
					,'needed_right' => 1
				)
				,'en' => array(
					'name' => 'games'
					,'full_name' => 'Games'
					,'title_suffix' => ' | Games'
					,'desc' => 'RPGCraft: Games.'
					,'needed_right' => 1
				)
			)
			,'improvements' => array(
				'hu' => array(
					'name' => 'fejlesztesek'
					,'full_name' => 'Fejlesztések'
					,'title_suffix' => ' | Fejlesztések'
					,'desc' => 'RPGCraft: Fejlesztések.'
				)
				,'en' => array(
					'name' => 'improvements'
					,'full_name' => 'Improvements'
					,'title_suffix' => ' | Improvements'
					,'desc' => 'RPGCraft: Improvements.'
				)
			)
			,'messages' => array(
				'hu' => array(
					'name' => 'uzenetek'
					,'full_name' => 'Üzenetek'
					,'title_suffix' => ' | Üzenetek'
					,'desc' => 'RPGCraft: Üzenetek.'
					,'needed_right' => 1
				)
				,'en' => array(
					'name' => 'messages'
					,'full_name' => 'Messages'
					,'title_suffix' => ' | Messages'
					,'desc' => 'RPGCraft: Messages.'
					,'needed_right' => 1
				)
			)
			,'developers' => array(
				'hu' => array(
					'name' => 'fejlesztok'
					,'full_name' => 'Fejlesztők'
					,'title_suffix' => ' | Fejlesztők'
					,'desc' => 'RPGCraft: Fejlesztők.'
				)
				,'en' => array(
					'name' => 'developers'
					,'full_name' => 'Developers'
					,'title_suffix' => ' | Developers'
					,'desc' => 'RPGCraft: Developers.'
				)
			)
			,'profile' => array(
				'hu' => array(
					'name' => 'profil'
					,'full_name' => 'Fejlesztők'
					,'title_suffix' => ' | Profil'
					,'desc' => 'RPGCraft: Profil.'
					,'needed_right' => 1
					,'css' => array(
						'css/index.css'
						,'js/jquery-ui-1.10.0.custom/css/smoothness/jquery-ui-1.10.0.custom.min.css'
					)
					,'js' => 'js/jquery-ui-1.10.0.custom/js/jquery-ui-1.10.0.custom.min.js'
				)
				,'en' => array(
					'name' => 'profile'
					,'full_name' => 'Profile'
					,'title_suffix' => ' | Profile'
					,'desc' => 'RPGCraft: Profile.'
					,'needed_right' => 1
					,'css' => array(
						'css/index.css'
						,'js/jquery-ui-1.10.0.custom/css/smoothness/jquery-ui-1.10.0.custom.min.css'
					)
					,'js' => 'js/jquery-ui-1.10.0.custom/js/jquery-ui-1.10.0.custom.min.js'
				)
			)
			,'registration' => array(
				'hu' => array(
					'name' => 'regisztracio'
					,'full_name' => 'Regisztráció'
					,'title_suffix' => ' | Regisztracio'
					,'desc' => 'RPGCraft: Regisztracio.'
					,'css' => array(
						'css/index.css'
						,'js/jquery-ui-1.10.0.custom/css/smoothness/jquery-ui-1.10.0.custom.min.css'
					)
					,'js' => 'js/jquery-ui-1.10.0.custom/js/jquery-ui-1.10.0.custom.min.js'
				)
				,'en' => array(
					'name' => 'registration'
					,'full_name' => 'Registration'
					,'title_suffix' => ' | Registration'
					,'desc' => 'RPGCraft: Registration.'
					,'css' => array(
						'css/index.css'
						,'js/jquery-ui-1.10.0.custom/css/smoothness/jquery-ui-1.10.0.custom.min.css'
					)
					,'js' => 'js/jquery-ui-1.10.0.custom/js/jquery-ui-1.10.0.custom.min.js'
				)
			)
			,'login' => array(
				'hu' => array(
					'name' => 'belepes'
					,'full_name' => 'Belépés'
					,'title_suffix' => ' | Belépés'
					,'desc' => 'RPGCraft: Belépés.'
					,'css' => 'css/index.css'
				)
				,'en' => array(
					'name' => 'login'
					,'full_name' => 'Login'
					,'title_suffix' => ' | Login'
					,'desc' => 'RPGCraft: Login.'
					,'css' => 'css/index.css'
				)
			)
		)
	);
	
?>
