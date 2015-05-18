<?php
	
	if ( !isset( $_GET['info'] ) ) {
		function curl($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		
		$num = isset( $_GET['num'] ) ? $_GET['num'] : 1 ;
		$min = isset( $_GET['min'] ) ? $_GET['min'] : 0 ;
		$max = isset( $_GET['max'] ) ? $_GET['max'] : 10 ;
		$col = isset( $_GET['col'] ) ? $_GET['col'] : 1 ;
		$base = isset( $_GET['base'] ) ? $_GET['base'] : 10 ;
		$format = ( isset( $_GET['format'] ) && $_GET['format']!='json' ) ? $_GET['format'] : 'plain' ;
		$rnd = isset( $_GET['rnd'] ) ? $_GET['rnd'] : 'new' ;
		
		$data = curl( "http://www.random.org/integers/?num=$num&min=$min&max=$max&col=$col&base=$base&format=$format&rnd=$rnd" );
		
		//echo 'http://www.random.org/integers/?num=$num&min=$min&max=$max&col=$col&base=$base&format=$format&rnd=$rnd";
		
		if ( $_GET['format']=='json' ) {
			header( 'Content-type: application/json' );
			echo json_encode( explode( "\n", $data ) );
		} else {
			header( 'Content-type: text/plain' );
			echo $data;
		}
	} else {
		header( 'Content-type: text/plain' );
		echo "Random generátor használata a random.org-on keresztül.\n";
		echo "Paraméterek: ?num=10&min=1&max=6&col=1&base=10&format=plain&rnd=new\n";
		echo "Bővebb leírás ezen a linken:\n";
		echo "http://www.random.org/clients/http/";
	}
	
?>