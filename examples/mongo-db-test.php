<?php
// create mongo client
if (class_exists('MongoClient')) {
	try {
		$mongo = new MongoClient();
	} catch (Exception $e) {
		die($e->getMessage()."\n");
	}
} else {
	die("MongoDB extension is not installed.\n");
}

// Use DB
$my_db = $mongo->selectDB("mydb");

// create collection if not present
$name = 'colRequests';
$collections = $my_db->getCollectionNames();
if (!in_array($name, $collections)) {
	$my_col = $my_db->createCollection($name, array(
		'capped'      => true,
		'size'        => 1024 * 1024 * 500,
		// 'max'         => 50000,
		'autoIndexId' => true,
	));
	// add index for value
	$my_col->ensureIndex(array('value' => 1));
} else {
	$my_col = $my_db->colRequests;
}

// new document
$now = time();
$value = mt_rand(1, 20000);
$doc_req = array(
	'date'  => date('Y-m-d', $now),
	'time'  => date('H:i:s', $now),
	'value' => $value,
);
$my_col->insert($doc_req);

// get all documents
$cursor = $my_col->find();
foreach($cursor as $doc) {
	echo sprintf("%12s | %12s | %s \n", $doc['date'], $doc['time'], $doc['value']);
}

// find one document
$params = array(
	'value' => $value
);
$doc = $my_col->findOne($params);
if (!empty($doc)) {
	echo sprintf("Last value: %s inserted on %s at %s \n", $doc['value'], $doc['date'], $doc['time']);
}

// find several documents
$val_lt = 5000;
$params = array(
	'value' => array(
		'$lt' => $val_lt
	)
);
$cursor = $my_col->find($params);
if (!empty($cursor)) {
	echo "Values lower than 5000: ";
	foreach($cursor as $doc) {
		echo "{$doc['value']} ";
	}
	echo "\n";
}
