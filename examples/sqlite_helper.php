<?php

require_once '../classes/sqlite-helper.php';

$table_test = 'CREATE TABLE IF NOT EXISTS "test" (
  "id" integer PRIMARY KEY AUTOINCREMENT NOT NULL,
  "name" char(128),
  "age" integer NOT NULL DEFAULT(0)
);';

$test_db = new SQLite3Helper('../tmp/test.db');
if (!$test_db) {
	die("Error opening database.\n");
}

$test_db->setTablesDefinitions(array($table_test));
$created = $test_db->createTables();
if (!$created) {
	echo "Error: {$test_db->lastErrorMsg()}\n";
	$test_db->close();
	die();
}

// insert rows
$row1 = array(
	'name' => "Jack Sparrow",
	'age' => 34,
);
$row1_id = $test_db->insertRow('test', $row1);
if ($row1_id === false) {
	echo "Error inserting row\n";
} else {
	echo "Row inserted: {$row1_id}\n";
}

// get rows
$rows = $test_db->getRows('test','id, name, age');
foreach ($rows as $row ) {
	echo sprintf("%8d | %20s | %3d \n", $row['id'], $row['name'], $row['age']);
}

$test_db->close();
