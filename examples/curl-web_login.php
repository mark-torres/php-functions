<?php
// http://kb4dev.com/tutorial/php-curl/php-access-the-password-protected-page

$login_url = "https://myanimelist.net/login.php?from=%2F";
$user = "myuser";
$pass = "mypass";

$user_field = "user_name";
$pass_field = "password";

$protected_url = "https://myanimelist.net/editprofile.php?go=myoptions";

// https://myanimelist.net/editprofile.php?go=myoptions

$login_data = array(
	$user_field => $user,
	$pass_field => $pass,
);

$cookie_file = "/tmp/php_cookies.txt";

$curl_opts = array(
	// CURLOPT_AUTOREFERER    => true,
	CURLOPT_NOPROGRESS     => true,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	// CURLOPT_COOKIESESSION  => true,
	CURLOPT_COOKIEJAR      => $cookie_file,
	CURLOPT_COOKIEFILE     => $cookie_file,
);

$login_handler = curl_init();
curl_setopt($login_handler, CURLOPT_URL, $login_url);
curl_setopt($login_handler, CURLOPT_COOKIESESSION, true);
curl_setopt($login_handler, CURLOPT_POST, true);
curl_setopt($login_handler, CURLOPT_POSTFIELDS, $login_data);
// common options
curl_setopt_array($login_handler, $curl_opts);

// attempt login
$landing_page_code = curl_exec($login_handler);
if (!curl_errno($login_handler)) {
	$info = curl_getinfo($login_handler);
	print_r($info);

	// reset options
	// curl_reset($login_handler);
	// curl_setopt_array($login_handler, $curl_opts);
	curl_setopt($login_handler, CURLOPT_COOKIESESSION, false);
	curl_setopt($login_handler, CURLOPT_POST, false);
	curl_setopt($login_handler, CURLOPT_URL, $protected_url);

	$protected_code = curl_exec($login_handler);
	if (!curl_errno($login_handler)) {
		echo $protected_code;
		$info = curl_getinfo($login_handler);
		print_r($info);
	} else {
		echo "ERROR!!\n";
	}
}

curl_close($login_handler);
