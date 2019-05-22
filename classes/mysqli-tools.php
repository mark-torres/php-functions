<?php
/**
* MySQLiTools adds some simplified functions to MySQLi
*/
class MySQLiTools extends mysqli
{
	public function getRows($table, $fields, $conditions = false) {
		$rows = array();
		if(!empty($table) && !empty($fields)) {
			$where = "";
			if(!empty($conditions)) {
				$where = "WHERE $conditions";
			}
			$query = "SELECT $fields FROM $table $where";
			$res = $this->query($query);
			if ($res->num_rows > 0) {
				$res->data_seek(0);
				while($row = $res->fetch_assoc()) {
					$rows[] = $row;
				}
				$res->free();
			}
		}
		return $rows;
	}

	public function getRowsAsKeyArray($table, $key_field, $fields, $conditions = false) {
		$rows = array();
		if(!empty($table) && !empty($key_field) && !empty($fields)) {
			$where = "";
			if(!empty($conditions)) {
				$where = "WHERE $conditions";
			}
			$query = "SELECT $key_field, $fields FROM $table $where";
			$res = $this->query($query);
			if ($res->num_rows > 0) {
				$res->data_seek(0);
				while($row = $res->fetch_assoc()) {
					$key = $row[$key_field];
					unset($row[$key_field]);
					$rows[ $key ] = $row;
				}
				$res->free();
			}
		}
		return $rows;
	}

	public function insertRow($table, $row_data) {
		$new_id = false;
		if(!empty($table) && !empty($row_data) && is_array($row_data)) {
			$fields = array_keys($row_data);
			$values = array_values($row_data);
			foreach($values as $i => $value) {
				$values[$i] = $this->escape_string($value);
				$fields[$i] = $this->escape_string($fields[$i]);
			}
			$query = "INSERT INTO $table (".implode(", ", $fields).") ".
				" VALUES ('".implode("', '", $values)."')";
			$inserted = $this->query($query);
			if($inserted !== false) {
				$new_id = $this->insert_id;
			}
		}
		return $new_id;
	}

	public function updateRow($table, $row_data, $condition) {
		$success = false;
		if(!empty($table) && !empty($row_data) && !empty($condition) && is_array($row_data)) {
			$set = array();
			foreach($row_data as $field => $value) {
				$value = $this->escape_string($value);
				$set[] = "$field = '$value'";
			}
			$set = implode(", ", $set);
			if(is_array($condition)) {
				$c = array();
				foreach($condition as $field => $value) {
					$value = $this->escape_string($value);
					$c[] = "$field = '$value'";
				}
				$where = implode(" AND ", $c);
			} else {
				$where = $condition;
			}
			$query = "UPDATE $table SET $set WHERE $where LIMIT 1";
			$success = $this->query($query);
		}
		return $success;
	}
}
