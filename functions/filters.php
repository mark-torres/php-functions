<?php
// ==========================================
// = SANITIZE FUNCTIONS USING PHP 5 FILTERS =
// ==========================================

// ===================================
// = FILTER INPUTS (POST, GET, etc.) =
// ===================================
function filter_input_type($type) {
	$input_types = array(
		'get'    => INPUT_GET,
		'post'   => INPUT_POST,
		'cookie' => INPUT_COOKIE,
		'server' => INPUT_SERVER,
		'env'    => INPUT_ENV,
	);
	if (array_key_exists($type, $input_types)) {
		return $input_types[ $type ];
	} else {
		return $input_types['post'];
	}
	
}
function filter_int_input($var_name, $type = 'post') {
	$filter_id = filter_id("int");
	$input_type = filter_input_type($type);
	$config = array();
	$config['options']['default'] = 0;
	return filter_input($input_type, $var_name, $filter_id, $config);
}

function filter_float_input($var_name, $type = 'post') {
	$filter_id = filter_id("float");
	$input_type = filter_input_type($type);
	$config = array();
	$config['options']['default'] = 0;
	return filter_input($input_type, $var_name, $filter_id, $config);
}

function filter_string_input($var_name, $type = 'post') {
	$filter_id = filter_id("string");
	$input_type = filter_input_type($type);
	return filter_input($input_type, $var_name, $filter_id);
}

function filter_special_chars_input($var_name, $type = 'post') {
	$filter_id = filter_id("special_chars");
	$input_type = filter_input_type($type);
	return filter_input($input_type, $var_name, $filter_id);
}

function filter_email_input($var_name, $type = 'post') {
	$filter_id = filter_id("validate_email");
	$input_type = filter_input_type($type);
	return filter_input($input_type, $var_name, $filter_id);
}

function filter_ip_input($var_name, $type = 'post') {
	$filter_id = filter_id("validate_ip");
	$input_type = filter_input_type($type);
	return filter_input($input_type, $var_name, $filter_id);
}

function filter_url_input($var_name, $type = 'post') {
	$filter_id = filter_id("validate_url");
	$input_type = filter_input_type($type);
	return filter_input($input_type, $var_name, $filter_id);
}

// ========================================
// = VALUES (Not from a GET/POST request) =
// ========================================
function filter_int($value) {
	$filter_id = filter_id("int");
	$config = array();
	$config['options']['default'] = 0;
	return filter_var($value, $filter_id, $config);
}

function filter_float($value) {
	$filter_id = filter_id("float");
	$config = array();
	$config['options']['default'] = (float)0;
	return filter_var($value, $filter_id, $config);
}

function filter_string($value) {
	$filter_id = filter_id("string");
	return filter_var($value, $filter_id);
}

function filter_special_chars($value) {
	$filter_id = filter_id("special_chars");
	return filter_var($value, $filter_id);
}

function strip_int($value) {
	$filter_id = filter_id("number_int");
	return filter_var($value, $filter_id, $config);
}

function strip_float($value) {
	$filter_id = filter_id("number_float");
	return filter_var($value, $filter_id, $config);
}

function strip_string($value) {
	$filter_id = filter_id("string");
	$flags = FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH;
	return filter_var($value, $filter_id, $flags);
}
