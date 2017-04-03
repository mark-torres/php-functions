<?php

/**
 * SQLite3 Helper
 * Tutorial: http://www.tutorialspoint.com/sqlite/sqlite_php.htm
 */
class SQLite3Helper extends SQLite3
{
	private $db_open = false;
	private $db_filename = "";
	private $tables_sql = array();

	function __construct($db_filename)
	{
		$this->db_filename = $db_filename;
		$this->open($db_filename);
	}

	// PUBLIC METHODS

	public function setTablesDefinitions($tables_definitions)
	{
		$this->tables_sql = $tables_definitions;
	}

	public function createTables()
	{
		foreach ($this->tables_sql as $sql ) {
			if (!$this->exec($sql)) {
				return false;
			}
		}
		return true;
	}

	public function getRows($table, $fields, $conditions = false) {
		$rows = array();
		if(!empty($table) && !empty($fields)) {
			$where = "";
			if(!empty($conditions)) {
				$where = "WHERE $conditions";
			}
			if (is_array($fields)) {
				$fields = implode(", ", $fields);
			}
			$query = "SELECT $fields FROM $table $where";
			$res = $this->query($query);
			while($row = $res->fetchArray(SQLITE3_ASSOC)) {
				$rows[] = $row;
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
			while($row = $res->fetchArray(SQLITE3_ASSOC)) {
				$key = $row[$key_field];
				unset($row[$key_field]);
				$rows[ $key ] = $row;
			}
		}
		return $rows;
	}

	public function insertRow($table, $row_data)
	{
		$new_id = false;
		if(!empty($table) && !empty($row_data) && is_array($row_data)) {
			$fields = array_keys($row_data);
			$values = array_values($row_data);
			foreach($values as $i => $value) {
				$values[$i] = $this->escapeString($value);
				$fields[$i] = $this->escapeString($fields[$i]);
			}
			$query = "INSERT INTO $table (".implode(", ", $fields).") ".
				" VALUES ('".implode("', '", $values)."')";
			$inserted = $this->exec($query);
			if($inserted) {
				$new_id = $this->lastInsertRowID();
			}
		}
		return $new_id;
	}

	public function updateRow($table, $row_data, $condition) {
		$success = false;
		if(!empty($table) && !empty($row_data) && !empty($condition) && is_array($row_data)) {
			$set = array();
			foreach($row_data as $field => $value) {
				$value = $this->escapeString($value);
				$set[] = "$field = '$value'";
			}
			$set = implode(", ", $set);
			if(is_array($condition)) {
				$c = array();
				foreach($condition as $field => $value) {
					$value = $this->escapeString($value);
					$c[] = "$field = '$value'";
				}
				$where = implode(" AND ", $c);
			} else {
				$where = $condition;
			}
			$query = "UPDATE $table SET $set WHERE $where LIMIT 1";
			$success = $this->exec($query);
		}
		return $success;
	}
}
