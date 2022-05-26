<?php
function curl_post( $url, $post_params, $secure = false) {
	$curl = curl_init();

	if( !$secure ) {
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
	}

	// curl_setopt( $curl, CURLOPT_SSL_CIPHER_LIST, "RC4-SHA" );
	$opts = array(
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST           => true,
		CURLOPT_POSTFIELDS     => http_build_query( $post_params ),
		// CURLOPT_HTTPHEADER     => array( 'Expect:' ),
	);
	curl_setopt_array( $curl, $opts );
	$response = curl_exec( $curl );
	if($response === false) {
		trigger_error(curl_error($curl), E_USER_WARNING);
	}
	return $response;
}

function curl_get( $url, $secure = false) {
	$curl = curl_init();

	if( !$secure ) {
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
	}

	$opts = array(
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true,
	);
	curl_setopt_array( $curl, $opts );
	$response = curl_exec( $curl );
	if($response === false) {
		trigger_error(curl_error($curl), E_USER_WARNING);
	}
	return $response;
}

