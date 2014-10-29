<?php
	header('content-type: application/json');

	// parse() function

	function parse( $encUrl ) {

		$options = array(
			CURLOPT_RETURNTRANSFER	=> true, 	// return web page
			CURLOPT_HEADER 			=> false, 	// don't return headers
			CURLOPT_FOLLOWLOCATION	=> true, 	// follow redirects
			CURLOPT_ENCODING	 	=> "", 		// handle all encodings
			CURLOPT_USERAGENT	 	=> 'Juiz', 	// who am i
			CURLOPT_AUTOREFERER 	=> true, 	// set referer on redirect
			CURLOPT_CONNECTTIMEOUT 	=> 5, 		// timeout on connect
			CURLOPT_TIMEOUT 		=> 10, 		// timeout on response
			CURLOPT_MAXREDIRS 		=> 3, 		// stop after 3 redirects
			CURLOPT_SSL_VERIFYHOST 	=> 0,
			CURLOPT_SSL_VERIFYPEER 	=> false,
		);
		$ch = curl_init();

		$options[CURLOPT_URL] = $encUrl;  
		curl_setopt_array($ch, $options);

		$content	= curl_exec( $ch );
		$err 		= curl_errno( $ch );
		$errmsg 	= curl_error( $ch );

		curl_close( $ch );

		if ($errmsg != '' || $err != '') {
			print_r($errmsg);
		}
		return $content;
	}


	// get counters

	$json = array('url'=>'','count'=>0);
	$url = $_GET['url'];
	$json['url'] = $url;
	$network = $_GET['nw'];
	

	
	if ( filter_var($url, FILTER_VALIDATE_URL) ) {

		if ( $network == 'google' ) {
			
			// http://www.helmutgranda.com/2011/11/01/get-a-url-google-count-via-php/
			$content = parse("https://plusone.google.com/u/0/_/+1/fastbutton?url=".$url."&count=true");
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = false;
			@$dom->loadHTML($content);
			$domxpath = new DOMXPath($dom);
			$newDom = new DOMDocument;
			$newDom->formatOutput = true;

			$filtered = $domxpath->query("//div[@id='aggregateCount']");

			if ( isset( $filtered->item(0)->nodeValue ) ) {
				$cars = array("u00c2", "u00a", 'Ã‚Â ', 'Ã‚Â', 'Ã', ',', 'Â', 'Â ');
				$count = str_replace($cars, '', $filtered->item(0)->nodeValue );
				$json['count'] = preg_replace( '#([0-9])#', '$1', $count );
			}

		}

		elseif ( $network == 'stumble' ) {
			
			$content = parse("http://www.stumbleupon.com/services/1.01/badge.getinfo?url=$url");

			$result = json_decode($content);
			if ( isset($result->result->views )) {
				$json['count'] = $result->result->views;
			}

		}
	}
	echo str_replace('\\/','/',json_encode($json));
?>