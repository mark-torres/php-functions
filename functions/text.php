<?php
// ===============================
// = TEXT MANIPULATION FUNCTIONS =
// ===============================

$foreign_characters = array(
	'/ä|æ|ǽ/' => 'ae',
	'/ö|œ/' => 'oe',
	'/ü/' => 'ue',
	'/Ä/' => 'Ae',
	'/Ü/' => 'Ue',
	'/Ö/' => 'Oe',
	'/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
	'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/' => 'a',
	'/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
	'/ç|ć|ĉ|ċ|č/' => 'c',
	'/Ð|Ď|Đ/' => 'D',
	'/ð|ď|đ/' => 'd',
	'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
	'/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
	'/Ĝ|Ğ|Ġ|Ģ/' => 'G',
	'/ĝ|ğ|ġ|ģ/' => 'g',
	'/Ĥ|Ħ/' => 'H',
	'/ĥ|ħ/' => 'h',
	'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ/' => 'I',
	'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/' => 'i',
	'/Ĵ/' => 'J',
	'/ĵ/' => 'j',
	'/Ķ/' => 'K',
	'/ķ/' => 'k',
	'/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
	'/ĺ|ļ|ľ|ŀ|ł/' => 'l',
	'/Ñ|Ń|Ņ|Ň/' => 'N',
	'/ñ|ń|ņ|ň|ŉ/' => 'n',
	'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',
	'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',
	'/Ŕ|Ŗ|Ř/' => 'R',
	'/ŕ|ŗ|ř/' => 'r',
	'/Ś|Ŝ|Ş|Š/' => 'S',
	'/ś|ŝ|ş|š|ſ/' => 's',
	'/Ţ|Ť|Ŧ/' => 'T',
	'/ţ|ť|ŧ/' => 't',
	'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
	'/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',
	'/Ý|Ÿ|Ŷ/' => 'Y',
	'/ý|ÿ|ŷ/' => 'y',
	'/Ŵ/' => 'W',
	'/ŵ/' => 'w',
	'/Ź|Ż|Ž/' => 'Z',
	'/ź|ż|ž/' => 'z',
	'/Æ|Ǽ/' => 'AE',
	'/ß/'=> 'ss',
	'/Ĳ/' => 'IJ',
	'/ĳ/' => 'ij',
	'/Œ/' => 'OE',
	'/ƒ/' => 'f'
);

// Global patterns
$hs_patterns = array(
	'email' => "/^([\w\.\-_]+)?\w+@[\w-_]+(\.\w+){1,}$/i",
	'ipv4' => "/\b(?:(?:2(?:[0-4][0-9]|5[0-5])|[0-1]?[0-9]?[0-9])\.){3}(?:(?:2([0-4][0-9]|5[0-5])|[0-1]?[0-9]?[0-9]))\b/",
	'domain' => "/^(([a-zA-Z0-9-_]{2,})\.){0,4}([a-zA-Z0-9-]{2,}\.[a-zA-Z-]{2,})$/i",
	'username' => "/[a-zA-Z][a-zA-Z0-9.\-_]{5,31}/",
);

// Replace all non-ASCII chars found in string by an ASCII char
// Punctuation chars are ignored
function asciify_string($string)
{
	global $foreign_characters;
	$ascii_string = "";
	$patterns = array_keys($foreign_characters);
	$replacements = array_values($foreign_characters);
	$ascii_string = preg_replace($patterns, $replacements, $string);
	return $ascii_string;
} // - - end of asciify_string - - - - -

function is_email($string)
{
	global $hs_patterns;
	return preg_match($hs_patterns['email'], $string);
} // - - end of is_email - - - - -

function is_ipv4($string)
{
	global $hs_patterns;
	return preg_match($hs_patterns['ipv4'], $string);
} // - - end of is_ipv4 - - - - -

function is_domain($string)
{
	global $hs_patterns;
	return preg_match($hs_patterns['domain'], $string);
} // - - end of is_domain - - - - -

function rand_string($algo = 0) {
	$algo = abs($algo);
	$algos = array(
		'sha1',
		'sha224',
		'sha256',
		'sha384',
	);
	$algo = empty($algos[$algo]) ? $algos[0] : $algos[$algo];
	$hash = hash($algo, crypt(time()."+".mt_rand()));
	return trim(base64_encode($hash),"=");
}
