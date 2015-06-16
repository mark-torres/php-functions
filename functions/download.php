<?php
/**
 * Based on the code here: http://www.w3bees.com/2013/09/download-file-from-remote-server-with.html
 */
function curl_download($file_url, $dst_path) {
	$result = false;
	// check if destination path is writable
	$dst_info = pathinfo($dst_path);
	if(!is_writable($dst_info['dirname'])) {
		trigger_error("Destination directory is not writable: ".$dst_info['dirname'], E_USER_WARNING);
		return false;
	}
	// remove file if already exists
	if (file_exists($dst_path)) {
		if (!unlink($dst_path)) {
			trigger_error("Error existing destination file", E_USER_WARNING);
			return false;
		}
	}
	$ch_headers = curl_init($file_url);
	curl_setopt( $ch_headers, CURLOPT_NOBODY, true );
	curl_setopt( $ch_headers, CURLOPT_RETURNTRANSFER, false );
	curl_setopt( $ch_headers, CURLOPT_HEADER, false );
	curl_setopt( $ch_headers, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch_headers, CURLOPT_MAXREDIRS, 3 );
	curl_setopt( $ch_headers, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch_headers, CURLOPT_SSL_VERIFYHOST, false );
	curl_exec( $ch_headers );
	$headers = curl_getinfo( $ch_headers );	
	curl_close( $ch_headers );
	if ($headers['http_code'] === 200) {
		// download file
		# open file to write
		$fp = fopen ($dst_path, 'w');
		# start curl
		$ch_file = curl_init();
		curl_setopt( $ch_file, CURLOPT_URL, $file_url );
		# set return transfer to false
		curl_setopt( $ch_file, CURLOPT_RETURNTRANSFER, false );
		curl_setopt( $ch_file, CURLOPT_BINARYTRANSFER, true );
		curl_setopt( $ch_file, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch_file, CURLOPT_SSL_VERIFYHOST, false );
		# increase timeout to download big file
		curl_setopt( $ch_file, CURLOPT_CONNECTTIMEOUT, 10 );
		# write data to local file
		curl_setopt( $ch_file, CURLOPT_FILE, $fp );
		# execute curl
		curl_exec( $ch_file );
		# close curl
		curl_close( $ch_file );
		# close local file
		fclose( $fp );

		if (filesize($dst_path) > 0) $result = true;
	} else {
		trigger_error("Error getting file. Status code ".$headers['http_code'], E_USER_WARNING);
	}
	
	return $result;
}
