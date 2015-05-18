<?php
	
	class HVRouting {
		var $c;
		var $params;
		var $langs;
		
		function __construct( array $config = array() ) {
			$this->c = array_merge(
				array(
					'langsupport'      => true
					,'supported_langs' => 'hu,en'
					,'def_lang' => 'hu'
					,'root'            => NULL
					,'route_by'        => $_SERVER['REQUEST_URI']
				), $config );
				
			$this->params = $this->get_url_params();
			$this->langs = explode( ',', $this->c['supported_langs'] );
		}
		
		function get_url_params(){
			if ($this->c['root']!=NULL) {
				$subfolder_count = count( explode('/', $this->c['root']) ) - 2;
				if ($subfolder_count<0) $subfolder_count = 0;
			} else $subfolder_count = 0;
			
			return array_slice( explode( '/', $this->c['route_by'] ), ($subfolder_count+1) );
		}
		
		function get_param( $i ){
			if ( $this->c['langsupport'] ) {
				if ( !in_array( $this->params[0], $this->langs ) )
					$params = $this->params;
				else
					$params = array_slice( $this->params, 1 );
			} else
				$params = $this->params;
			
			return $params[$i];
		}
		
		function get_lang(){
			if ( $this->c['langsupport'] ) {
				if ( !in_array( $this->params[0], $this->langs ) )
					return $this->c['def_lang'];
				else
					return $this->params[0];
			}
				
			return;
		}
		
		function get_lang_to_url(){
			if ( $this->c['langsupport'] ) {
				$l = $this->get_lang();
				
				if ( $l != $this->c['def_lang'] )
					return $l.'/';
			}
				
			return;
		}
		
		function get_lang_change_links() {
			/*
			$r = str_replace( PAGE_ROOT, '', $_SERVER['REQUEST_URI'] );
			
			if ($this->c['def_lang']!=PAGE_LANG || strpos( $r, ($this->c['def_lang'].'/') )===0 )
				$r = substr( $r, 3);
				
			$arr = array();
			foreach ($this->langs as $lname)
				$arr[$lname] = PAGE_ROOT.$lname.'/'.$r;
			
			return $arr;
			*/
			$arr = array();
			foreach ($this->langs as $lname)
				$arr[$lname] = PAGE_ROOT.(( ($this->c['def_lang']!=$lname) ) ? $lname.'/' : '');
			return $arr;
		}
		
		function get_config(){
			return $this->c;
		}
	}
	
?>