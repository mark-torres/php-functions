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

	private function is_date($value)
	{
		return $value == date('Y-m-d', strtotime($value));
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

	public function readDate($name, $default = "")
	{
		if (!is_string($default)) {
			$default = "";
		}
		if (!array_key_exists($name, $this->array)) {
			return $default;
		}
		$value = $this->array[$name];
		if (!$this->is_date($value)) {
			return $default;
		}
		return $this->array[$name];
	}

	public function readCSV($name, $default = [])
	{
		if (!is_array($default)) {
			$default = [];
		}
		if (!array_key_exists($name, $this->array)) {
			return $default;
		}
		$value = $this->array[$name];
		if (empty($value) || !is_string($value)) {
			return $default;
		}
		$value = trim($value, " ,");
		if (empty($value)) {
			return $default;
		}
		$list = preg_split("/\s*,\s*/", $value);
		return $list;
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
		}
		return $this->array[$name];
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
		}
		return $this->array[$name];
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
		if (!$this->is_date($value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		return $this->array[$name];
	}

	public function readOption($name, $options, $default = "")
	{
		if (!is_array($options)) {
			$options = [];
		}
		if (!array_key_exists($name, $this->array)) {
			return $default;
		}
		$value = $this->array[$name];
		if (!in_array($value, $options)) {
			return $default;
		}
		return $value;
	}

	public function requireOption($name, $options, $err_msg = "")
	{
		if (!is_array($options)) {
			$options = [];
		}
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "La opcion '{$name}' es requerida";
		}
		if (!array_key_exists($name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $this->array[$name];
		if (!in_array($value, $options)) {
			$this->errors[] = $err_msg;
			return "";
		}
		return $value;
	}

	public function requireCSV($name, $err_msg = "")
	{
		$default = [];
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "La lista de valores '{$name}' es requerida";
		}
		if (!array_key_exists($name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $this->array[$name];
		if (empty($value) || !is_string($value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = trim($value, " ,");
		if (empty($value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$list = preg_split("/\s*,\s*/", $value);
		return $list;
	}
}
