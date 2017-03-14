<?php
	/**
		@author: Hubert Viktor
		@since: 2012
		
		HV-ADMIN v1.0
		
	*/
	
	class HVTemplate {
		var $c; //Template Config
		var $c_ini; //Standard Config
		var $url_params; //The parameters extracted from the URL.
		
		/*
			Constructor, which creates the head part of the html,
			and also does other things based on the configuration.
			
			Conditional JS/CSS including
				Based on the URL parameters, the appropriate css and js files will be included. (If exists)
				Example: /website/home/
					try including: /website/css/home.css, /website/js/home.js
			
			Parameters:
				session_start - true
					Starts the session if true, and session didn't start yet.
				css_list - NULL
					The css styles to include at the head.
				js_list - NULL
					The js scripts to include at the bottom of the body.
				js_header_list - NULL
					The js scripts that must be included at the head.
				config - 'config.ini'
					The configuration file to load.
				root - NULL
					The folder's url, where the website is.
					Example:
						Site: http://site.com/folder/folder/zeewebsite/
						root parameter: '/folder/folder/zeewebsite/'
				pages - NULL
					Support multilanguage
					Structure:
						array() - The php file's name, thet corresponds to the menu
							array() - The menu's names in different languages
					Example:
						array(
							'home' => array(
								'home',
								'fooldal'
							)
						)
				default_page - NULL
					The default page. Example: 'home'
				doctype - 'trans'
					Defines which doctype must be used. options: trans/strict
		*/
		function __construct( array $config ) {
			$this->c = array_merge(
				array(
					'session_start'     => true
					,'favicon'          => NULL
					,'css_list'         => NULL
					,'js_list'          => NULL
					,'js_header_list'   => NULL
					,'config'           => 'config.ini'
					,'root'             => NULL
					,'def_alt'          => 'Default alt attribute'
					,'def_title'        => 'Default title'
					,'def_desc'         => 'Default description'
					,'pages'            => NULL
					,'default_page'     => NULL
					,'doctype'          => 'trans' //trans, strict, fb
					,'fb_images'        => NULL
					,'fb_url'           => NULL
					,'user_right'       => 0
					,'setup_js_vals'    => true
				), $config );
				
			$c = $this->c['rootObj']->get_config();
			
			//echo "ASDASD - " . $this->c['rootObj']->get_param(0) . " - ASDASD";
			
			/*Start Session*/
			//if ($this->c['session_start'] && strlen(session_id())==0) session_start();
			//Deprecated for this project.
			
			/*Load Config File*/
			if ( is_file($this->c['config']) )
				$this->c_ini = parse_ini_file($this->c['config'], true);
			else
				throw new Exception( 'Missing config.ini file. (' . $this->c['config'] . ')', 100 );
			
			/*Get URL-parameters*/
			if ($this->c['pages']==NULL || count($this->c['pages'])==0)
				throw new Exception( 'Empty or missing pages array.', 101 );
			$this->url_params = $this->get_url_params();
			
			/*Set up DOCTYPE*/
			if ($this->c['doctype']=='trans') echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
			else if ($this->c['doctype']=='trans') echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
			else if ($this->c['doctype']=='fb') echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">';
			
			$fb_doc = $this->c['doctype']=='fb';
			
			/* Setup Language */
			define( 'PAGE_ROOT', $this->c['root'] );
			define( 'PAGE_URL_ROOT', $this->get_url_root() );
			
			define( 'PAGE_LANG', $this->get_lang() );
			define( 'PAGE_LANG_TO_URL', $this->get_lang_to_url() );
			define( 'PAGE_FILE_NAME', $this->get_page_filename() );
			
			define( 'USER_RIGHT', $this->c['user_right'] );
			
			$full_title = @$this->c['title_prefix'].$this->get_title().@$this->c['title_suffix'];
			/*Set up header*/
			echo "
				<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					<title>".$full_title."</title>
					".(($fb_doc)?'<meta property="og:title" content="'.$full_title.'" />':'')."
					<meta ".(($fb_doc)?"property='og:description'":"")." name='description' content='".$this->get_desc()."' />
				";
				
			if ( isset( $this->c['fb_images'] ) ) {
				foreach ($this->c['fb_images'] as $image) {
					echo '<meta property="og:image" content="'.$image.'" />';
				}
			}
			if ( isset( $this->c['fb_url'] ) )
				echo '<meta property="og:url" content="'.$this->c['fb_url'].'" />';
			
			if ( $this->c['favicon']!=NULL )
					echo "<link rel='shortcut icon' type='image/x-icon' href='".$this->c['root'].$this->c['favicon']."' />";
			
			/*Get CSS list*/
			if ($this->c['css_list']!=NULL && is_array($this->c['css_list'])) {
				foreach ($this->c['css_list'] as $css_file) {
					if ( is_file($css_file) )
						echo "<link href='".$this->c['root']."$css_file' type='text/css' rel='stylesheet' />";
					else
						echo "<!-- Given stylesheet does not exist: $css_file -->";
				}
			}
			/*Conditional CSS*/
			$cond_css = 'css/'.$this->get_page_filename().'.css';
			if ( is_file( $cond_css ) )
				echo "<link href='".$this->c['root']."$cond_css' type='text/css' rel='stylesheet' />";
			
			/* Page Conditional CSS */
			/*
			if ( $this->c['pages'][PAGE_FILE_NAME]['css']!=NULL ) {
				if ( is_array( $this->c['pages'][PAGE_FILE_NAME]['css'] ) ) {
					foreach ($this->c['pages'][PAGE_FILE_NAME]['css'] as $css_file) {
						if ( is_file($css_file) )
						echo "<link href='".$this->c['root']."$css_file' type='text/css' rel='stylesheet' />";
						else
							echo "<!-- Given StyleSheet does not exist: $css_file -->";
					}
				} else {
					$css_file = $this->c['pages'][PAGE_FILE_NAME]['css'];
					if ( is_file( $css_file ) )
						echo "<link href='".$this->c['root']."$css_file' type='text/css' rel='stylesheet' />";
					else
						echo "<!-- Given StyleSheet does not exist: $css_file -->";
				}
			}
			*/
			
			if (
				($c['langsupport'] && @$this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['css']!=NULL) ||
				(!$c['langsupport'] && @$this->c['pages'][PAGE_FILE_NAME]['css']!=NULL)
				) {
					
				if (
					($c['langsupport'] && is_array( $this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['css'] ) ) ||
					(!$c['langsupport'] && !is_array( $this->c['pages'][PAGE_FILE_NAME]['css'] ) )
					) {
					$arr = ( $c['langsupport'] ) ? $this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['css'] : $this->c['pages'][PAGE_FILE_NAME]['css'];
					foreach ($arr as $css_file) {
						if ( is_file($css_file) )
							echo "<link href='".$this->c['root']."$css_file' type='text/css' rel='stylesheet' />";
						else
							echo "<!-- Given JavaScript does not exist: $css_file -->";
					}
				} else {
					$css_file = ( $c['langsupport'] ) ? $this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['css'] : $this->c['pages'][PAGE_FILE_NAME]['css'];
					if ( is_file( $css_file ) )
						echo "<link href='".$this->c['root']."$css_file' type='text/css' rel='stylesheet' />";
					else
						echo "<!-- Given JavaScript does not exist: $css_file -->";
				}
				
			}
			
			/* Setup JS Variables */
			echo "
				<script type='text/javascript'>
					var PAGE_LANG = '".PAGE_LANG."';
					var PAGE_ROOT = '".PAGE_ROOT."';
				</script>";
			
			/*JS that MUST be included in the header*/
			if ($this->c['js_header_list']!=NULL && is_array($this->c['js_header_list'])) {
				foreach ($this->c['js_header_list'] as $js_file) {
					if ( is_file($js_file) )
						echo "<script src='".$this->c['root']."$js_file' type='text/javascript'></script>";
					else
						echo "<!-- Given JavaScript does not exist: $js_file -->";
				}
			}
			/* Page Conditional JS that MUST be included in the header */
			if ( @$this->c['pages'][PAGE_FILE_NAME]['js_header']!=NULL ) {
				if ( is_array( $this->c['pages'][PAGE_FILE_NAME]['js_header'] ) ) {
					foreach ($this->c['pages'][PAGE_FILE_NAME]['js_header'] as $js_file) {
						if ( is_file($js_file) )
							echo "<script src='".$this->c['root']."$js_file' type='text/javascript'></script>";
						else
							echo "<!-- Given JavaScript does not exist: $js_file -->";
					}
				} else {
					$js_file = $this->c['pages'][PAGE_FILE_NAME]['js_header'];
					if ( is_file( $js_file ) )
						echo "<script src='".$this->c['root']."$js_file' type='text/javascript'></script>";
					else
						echo "<!-- Given JavaScript does not exist: $js_file -->";
				}
			}
			
			echo "</head><body>";
		}
		
		/*
			Setting up the footer
			Includes scripts (requested and conditional ones) and closes the document.
		*/
		function setup_footer(){
			$c = $this->c['rootObj']->get_config();
			
			if ($this->c['js_list']!=NULL && is_array($this->c['js_list'])) {
				foreach ($this->c['js_list'] as $js_file) {
					if ( is_file($js_file) )
						echo "<script src='".$this->c['root']."$js_file' type='text/javascript'></script>";
					else
						echo "<!-- Given JavaScript does not exist: $js_file -->";
				}
			}
				
			/* Page Conditional JS */
			if (
				($c['langsupport'] && @$this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['js']!=NULL) ||
				(!$c['langsupport'] && @$this->c['pages'][PAGE_FILE_NAME]['js']!=NULL)
				) {
					
				if (
					($c['langsupport'] && is_array( $this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['js'] ) ) ||
					(!$c['langsupport'] && !is_array( $this->c['pages'][PAGE_FILE_NAME]['js'] ) )
					) {
					$arr = ( $c['langsupport'] ) ? $this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['js'] : $this->c['pages'][PAGE_FILE_NAME]['js'];
					foreach ($arr as $js_file) {
						if ( is_file($js_file) )
							echo "<script src='".$this->c['root']."$js_file' type='text/javascript'></script>";
						else
							echo "<!-- Given JavaScript does not exist: $js_file -->";
					}
				} else {
					$js_file = ( $c['langsupport'] ) ? $this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['js'] : $this->c['pages'][PAGE_FILE_NAME]['js'];
					if ( is_file( $js_file ) )
						echo "<script src='".$this->c['root']."$js_file' type='text/javascript'></script>";
					else
						echo "<!-- Given JavaScript does not exist: $js_file -->";
				}
				
			}
			
			/*Conditional JS*/
			$cond_js = 'js/'.$this->get_page_filename().'.js';
			if ( is_file( $cond_js ) )
				echo "<script src='".$this->c['root']."$cond_js' type='text/javascript'></script>";
			
			/* Closing */
			echo "</body></html>";
		}
		
		/*Returns the URL parameters (Favorably index 0 contains the actual menu)*/
		function get_url_params(){
			$this->c['rootObj']->get_url_params();
		}
		
		/*
			Gets the current page filename. It's needed due to multiple language usage.
			Example: Both 'home' and 'fooldal' points to the same 'fooldal.php' pagefile.
			More language can be added like this.
		*/
		function get_page_filename() {
			$menu = $this->c['rootObj']->get_param(0);
			
			$p = $this->c['pages']; //Valid Pages
			
			if ( empty($menu) )
				return $this->c['default_page'];
			
			if ( $menu!='404' ) {
				
				foreach ( $p as $inc_name => $menus ) {
					$c = $this->c['rootObj']->get_config();
					if ( $c['langsupport'] ) {
						if ( $menu == $menus[PAGE_LANG]['name'] )
							return $inc_name;
					} else {
						if ( $menu == $menus['name'] )
							return $inc_name;
					}
				}
				
			}
			return '404';
			
		}
		
		/*Includes the current page from: '/pages/<menu>.php' */
		function load_current_page() {
			$c = $this->c['rootObj']->get_config();
			
			$pagename = $this->get_page_filename();
			if ( $c['langsupport'] )
				$pageright = @$this->c['pages'][$pagename][PAGE_LANG]['needed_right'];
			else
				$pageright = $this->c['pages'][$pagename]['needed_right'];

			$authorized = $this->c['user_right'] >= $pageright;
			
			if ( $authorized ) {
				$inc = 'pages/'.$pagename.'.php';
				
				if ( is_file($inc) )
					return $inc;
				else throw new Exception( 'Given pagefile does not exist. (' . $inc . ')' , 200 );
			} else throw new Exception( 'Access denied.' , 300 );
			
		}
		
		/*Simple Getters*/
		function get_url_root(){
			return ( $this->c['root'] ).( $this->get_lang_to_url() );
		}
		
		function get_config(){
			return $this->c_ini;
		}
		
		function get_lang(){
			return $this->c['rootObj']->get_lang();
		}
		function get_lang_to_url(){
			return $this->c['rootObj']->get_lang_to_url();
		}
		function get_lang_change_links() {
			return $this->c['rootObj']->get_lang_change_links();
		}
		function get_page_link( $pagename ){
			$c = $this->c['rootObj']->get_config();
			if ( $c['langsupport'] )
				return
					PAGE_ROOT.
					( ( PAGE_LANG==$c['def_lang'] ) ? '' : PAGE_LANG.'/' ).
					$this->c['pages'][$pagename][PAGE_LANG]['name'];
			else
				return
					PAGE_ROOT.
					$this->c['pages'][$pagename]['name'];
		}
		function get_title() {
			$c = $this->c['rootObj']->get_config();
			if ( $c['langsupport'] )
				return
					@$this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['title_prefix']
					.
					$this->c['def_title']
					.
					@$this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['title_suffix'];
			else
				return
					@$this->c['pages'][PAGE_LANG]['title_prefix']
					.
					$this->c['def_title']
					.
					@$this->c['pages'][PAGE_LANG]['title_suffix'];
		}
		function get_desc() {
			$c = $this->c['rootObj']->get_config();
			if ( $c['langsupport'] )
				$d = @$this->c['pages'][PAGE_FILE_NAME][PAGE_LANG]['desc'];
			else
				$d = @$this->c['pages'][PAGE_LANG]['desc'];
				
			if ( $d=='' )
				return $this->c['def_desc'];
			
			return $d;
		}
	}
	
?>
