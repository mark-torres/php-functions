<?php
require_once '../classes/HSDataFilter.php';

$filter = new HSDataFilter();

$values = array(
	'123',
	'+123',
	'-123',
	'123.1',
	'+123.1',
	'-123.1',
	'yes',
	'true',
	'1',
	'on',
	'no',
	'false',
	'0',
	'off',
	'mail@example.com.no',
	'mail @ example.com.no',
	'mail+sasasa@example.com.no',
	'mail+sasasa@example',
	'example.com.no',
	'https://www.php.net/manual/en/function.filter-var.php',
	'https://www.php.net/',
	'https://www.php.net',
	'/manual/en/function.filter-var.php',
	'191.148.205.15',
	'191.148.2015',
	'2001:0db8:0000:0042:0000:8a2e:0370:7334',
	'2001:0dk8:0000:0042:0000:8a2e:0370:7334',
	'2001:0db8:0000:004000:8a2e:0370:7334',
	'3c:07:54:39:89:de',
	'3c:07:54:x9:89:de',
	'3c:07:54:39:de',
);

$pad = 60;

echo "Test for INTEGER:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isInt($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for TRUE:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isTrue($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for FLOAT:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isFloat($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for EMAIL:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isEmail($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for URL:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isUrl($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for URL path:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isUrlPath($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for DOMAIN:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isDomain($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for HOST:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isHost($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for IP:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isIP($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for IPv4:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isIPv4($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for IPv6:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isIPv6($value)) ? "YES" : "NO";
	echo "\n";
}
echo "\n";
echo "Test for MAC:\n";
foreach($values as $value) {
	echo str_pad($value, $pad, ".", STR_PAD_RIGHT);
	echo ($filter->isMac($value)) ? "YES" : "NO";
	echo "\n";
}
