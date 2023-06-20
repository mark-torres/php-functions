<?php
class HSReadRequest
{
	private $errors;
	private $array;
	private $subArray;

	function __construct($array_req)
	{
		$this->array = $array_req;
		$this->subArray = false;
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

	public function readNumberAK($arr_name, $name, $default = 0)
	{
		if (!array_key_exists($arr_name, $this->array)) {
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->readNumber($name, $default);
		$this->subArray = false;
		return $value;
	}

	public function readNumber($name, $default = 0)
	{
		if (!is_numeric($default)) {
			$default = 0;
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			return $default;
		}
		$value = $array[$name];
		if (!is_numeric($value) || empty($value)) {
			return $default;
		}
		return $value;
	}

	public function readStringAK($arr_name, $name, $default = "")
	{
		if (!array_key_exists($arr_name, $this->array)) {
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->readString($name, $default);
		$this->subArray = false;
		return $value;
	}

	public function readString($name, $default = "")
	{
		if (!is_string($default)) {
			$default = "";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			return $default;
		}
		$value = $array[$name];
		if (!is_string($value) || strlen($value) == 0) {
			return $default;
		}
		return $value;
	}

	public function readArrayAK($arr_name, $name, $default = [])
	{
		if (!array_key_exists($arr_name, $this->array)) {
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->readArray($name, $default);
		$this->subArray = false;
		return $value;
	}

	public function readArray($name, $default = [])
	{
		if (!is_array($default)) {
			$default = [];
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			return $default;
		}
		$value = $array[$name];
		if (!is_array($value) || empty($value)) {
			return $default;
		}
		return $value;
	}

	public function readDateAK($arr_name, $name, $default = "")
	{
		if (!array_key_exists($arr_name, $this->array)) {
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->readDate($name, $default);
		$this->subArray = false;
		return $value;
	}

	public function readDate($name, $default = "")
	{
		if (!is_string($default)) {
			$default = "";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			return $default;
		}
		$value = $array[$name];
		if (!$this->is_date($value)) {
			return $default;
		}
		return $value;
	}

	public function readCSVAK($arr_name, $name, $default = [])
	{
		if (!array_key_exists($arr_name, $this->array)) {
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->readCSV($name, $default);
		$this->subArray = false;
		return $value;
	}

	public function readCSV($name, $default = [])
	{
		if (!is_array($default)) {
			$default = [];
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			return $default;
		}
		$value = $array[$name];
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

	public function readPatternAK($arr_name, $name, $pattern, $default = "")
	{
		if (!array_key_exists($arr_name, $this->array)) {
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->readPattern($name, $pattern, $default);
		$this->subArray = false;
		return $value;
	}

	public function readPattern($name, $pattern, $default = '')
	{
		if (!is_string($default)) {
			$default = '';
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			return $default;
		}
		$value = $array[$name];
		if (!preg_match($pattern, $value)) {
			return $default;
		}
		return $value;
	}

	public function readOptionsAK($arr_name, $name, $options, $default = "")
	{
		if (!array_key_exists($arr_name, $this->array)) {
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->readOption($name, $options, $default);
		$this->subArray = false;
		return $value;
	}

	public function readOption($name, $options, $default = "")
	{
		if (!is_array($options)) {
			$options = [];
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			return $default;
		}
		$value = $array[$name];
		if (!in_array($value, $options)) {
			return $default;
		}
		return $value;
	}

	public function requireNumberAK($arr_name, $name, $err_msg = "")
	{
		$default = 0;
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo numérico '{$arr_name}[{$name}]' es requerido";
		}
		if (!array_key_exists($arr_name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->requireNumber($name, $err_msg);
		$this->subArray = false;
		return $value;
	}

	public function requireNumber($name, $err_msg = "")
	{
		$default = 0;
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo numérico '{$name}' es requerido";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $array[$name];
		if (!is_numeric($value) || empty($value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		return $value;
	}

	public function requireStringAK($arr_name, $name, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo de texto '{$arr_name}[{$name}]' es requerido";
		}
		if (!array_key_exists($arr_name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->requireString($name, $err_msg);
		$this->subArray = false;
		return $value;
	}

	public function requireString($name, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo de texto '{$name}' es requerido";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = trim($array[$name]);
		if (!is_string($value) || strlen($value) == 0) {
			$this->errors[] = $err_msg;
			return $default;
		}
		return $value;
	}

	public function requireArrayAK($arr_name, $name, $err_msg = "")
	{
		$default = [];
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El arreglo '{$arr_name}[{$name}]' es requerido";
		}
		if (!array_key_exists($arr_name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->requireArray($name, $err_msg);
		$this->subArray = false;
		return $value;
	}

	public function requireArray($name, $err_msg = "")
	{
		$default = [];
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El arreglo '{$name}' es requerido";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $array[$name];
		if (!is_array($value) || empty($value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		return $value;
	}

	public function requireDateAK($arr_name, $name, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo de fecha '{$arr_name}[{$name}]' es requerido";
		}
		if (!array_key_exists($arr_name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->requireDate($name, $err_msg);
		$this->subArray = false;
		return $value;
	}

	public function requireDate($name, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo de fecha '{$name}' es requerido";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $array[$name];
		if (!$this->is_date($value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		return $value;
	}

	public function requireCSVAK($arr_name, $name, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "La lista de valores '{$arr_name}[{$name}]' es requerida";
		}
		if (!array_key_exists($arr_name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->requireCSV($name, $err_msg);
		$this->subArray = false;
		return $value;
	}

	public function requireCSV($name, $err_msg = "")
	{
		$default = [];
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "La lista de valores '{$name}' es requerida";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $array[$name];
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

	public function requirePatternAK($arr_name, $name, $pattern, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo '{$arr_name}[{$name}]' no tiene el formato requerido";
		}
		if (!array_key_exists($arr_name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->requirePattern($name, $pattern, $err_msg);
		$this->subArray = false;
		return $value;
	}

	public function requirePattern($name, $pattern, $err_msg = '')
	{
		$default = '';
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "El campo '{$name}' no tiene el formato requerido";
		}
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $array[$name];
		if (!preg_match($pattern, $value)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		return $value;
	}

	public function requireOptionAK($arr_name, $name, $options, $err_msg = "")
	{
		$default = "";
		if (empty($err_msg) || !is_string($err_msg)) {
			$err_msg = "La opcion '{$arr_name}[{$name}]' es requerida";
		}
		if (!array_key_exists($arr_name, $this->array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$this->subArray = $arr_name;
		$value = $this->requireOption($name, $options, $err_msg);
		$this->subArray = false;
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
		$array = ($this->subArray !== false) ? $this->array[$this->subArray] : $this->array;
		if (!array_key_exists($name, $array)) {
			$this->errors[] = $err_msg;
			return $default;
		}
		$value = $array[$name];
		if (!in_array($value, $options)) {
			$this->errors[] = $err_msg;
			return "";
		}
		return $value;
	}
}
