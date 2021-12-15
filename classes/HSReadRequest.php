<?php
class HSReadRequest
{
	private $errors;
	private $array;

	function __construct($array_req)
	{
		$this->array = $array_req;
		$this->errors = [];
	}

	public function errors()
	{
		return $this->errors;
	}

	public function readRawRequest()
	{
		return file_get_contents('php://input');
	}

	public function readNumber($name, $default = 0)
	{
		if (!is_numeric($default)) {
			$default = 0;
		}
		if (!array_key_exists($name, $this->array)) {
			return $default;
		}
		$value = $this->array[$name];
		if (!is_numeric($value) || empty($value)) {
			return $default;
		}
		return $this->array[$name];
	}

	public function readString($name, $default = "")
	{
		if (!is_string($default)) {
			$default = "";
		}
		if (!array_key_exists($name, $this->array)) {
			return $default;
		}
		$value = $this->array[$name];
		if (!is_string($value) || strlen($value) == 0) {
			return $default;
		}
		return $this->array[$name];
	}

	public function readArray($name, $default = [])
	{
		if (!is_array($default)) {
			$default = [];
		}
		if (!array_key_exists($name, $this->array)) {
			return $default;
		}
		$value = $this->array[$name];
		if (!is_array($value) || empty($value)) {
			return $default;
		}
		return $this->array[$name];
	}

	public function requireNumber($name, $err_msg = "")
	{
		$default = 0;
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo numérico '{$name}' es requerido";
		}
		if (!array_key_exists($name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $this->array[$name];
		if (!is_numeric($value) || empty($value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		return $this->array[$name];
	}

	public function requireString($name, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo de texto '{$name}' es requerido";
		}
		if (!array_key_exists($name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $this->array[$name];
		if (!is_string($value) || strlen($value) == 0) {
			$this->errors[] = $err_msg;
			return $default;
		} else {
			return $this->array[$name];
		}
	}

	public function requireArray($name, $err_msg = "")
	{
		$default = [];
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El arreglo '{$name}' es requerido";
		}
		if (!array_key_exists($name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $this->array[$name];
		if (!is_array($value) || empty($value)) {
			$this->errors[] = $err_msg;
			return $default;
		} else {
			return $this->array[$name];
		}
	}

	public function requireDate($name, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo de fecha '{$name}' es requerido";
		}
		if (!array_key_exists($name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $this->array[$name];
		if ($value != date('Y-m-d', strtotime($value))) {
			$this->errors[] = $err_msg;
			return $default;
		} else {
			return $this->array[$name];
		}
	}
}
