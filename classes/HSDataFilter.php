<?php

/**
 * HSDataFilter
 */
class HSDataFilter
{
	function __construct()
	{
		// code...
	}

	public function isInt($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_INT) !== FALSE);
	}

	public function isFloat($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_FLOAT) !== FALSE);
	}

	public function isEmail($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_EMAIL) !== FALSE);
	}

	public function isUrl($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_URL) !== FALSE);
	}

	public function isUrlPath($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) !== FALSE);
	}

	public function isTrue($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_BOOLEAN) !== FALSE);
	}

	public function isDomain($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_DOMAIN) !== FALSE);
	}

	public function isHost($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== FALSE);
	}

	public function isIP($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_IP) !== FALSE);
	}

	public function isIPv4($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== FALSE);
	}

	public function isIPv6($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== FALSE);
	}

	public function isMac($test_value)
	{
		return (filter_var($test_value, FILTER_VALIDATE_MAC) !== FALSE);
	}
}
