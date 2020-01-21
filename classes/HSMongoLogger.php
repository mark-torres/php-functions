<?php
/**
 * HSMongoLogger
 *
 * A simple logger class using MongoDB (WiP). Requires the MongoDB Driver (PHPLIB).
 */
class HSMongoLogger
{
	/** @var collection The collection holding the logs. */
	private $collection;

	/** @var collection_name Name for the collection. */
	private $collection_name;

	/** @var collection_size Size for the collection in MB. */
	private $collection_size;

	/**
	 * Constructor
	 *
	 * Requires a name and optional size in MB for the collection that will hold the logs.
	 *
	 * @param string $name Name for the collection.
	 *
	 * @param integer $size Size for the collection in MB.
	 *
	 * @return HSMongoLogger.
	 */
	function __construct($name, $size = 1)
	{
		if (empty($name) || !is_string($name)) {
			trigger_error("A collection name is required.", E_USER_ERROR);
		}
		if (empty($size) || !is_numeric($size)) {
			$options['size'] = 1;
		}
		$this->collection_name = $name;
		$this->collection_size = abs((int) $size);
		// validate
		if (!preg_match("/^[a-z][a-z_\d]+$/i", $this->collection_name)) {
			trigger_error(
				"Collection name is not valid. Follow the pattern: /^[a-z][a-z_\\d]+$/i.",
				E_USER_ERROR
			);
		}
		if ($this->collection_size > 100) {
			$this->collection_size = 1;
		}
	}

	/**
	 * Connect to MongoDB
	 *
	 * Connects to the server and creates the collection if necessary. Receives a connection string
	 * and if not provided, the default value is `mongodb://localhost:27017`.
	 *
	 * @param string $conn_string The connection string.
	 *
	 * @return boolean
	 */
	public function connect($conn_string = '')
	{
		// connect to localhost by default
		if (empty($conn_string)) {
			$conn_string = "mongodb://localhost:27017";
		}
		try {
			$client = new MongoDB\Client($conn_string);
			$db = $client->selectDatabase('hs_mongo_logger');
			$cols = $db->listCollections();
			$col_names = array();
			foreach ($cols as $col) {
				$col_names[] = $col->getName();
			}
			$this->collection = false;
			if (!in_array($this->collection_name, $col_names)) {
				// create simple collection
				$opts = array(
					'caped' => true,
					'size' => 1024 * 1024 * $this->collection_size, // 100 MB
				);
				$db->createCollection($this->collection_name, $opts);
				$this->collection = $db->selectCollection($this->collection_name);
				$this->collection->createIndexes([
					[
						'key' => ['timestamp' => 1]
					],
					[
						'key' => ['application' => 1]
					],
				]);
			} else {
				$this->collection = $db->selectCollection($this->collection_name);
			}
		} catch (Exception $e) {
			trigger_error($e->getMessage(), E_USER_ERROR);
			return false;
		}
		return true;
	}


	/**
	 * Save a new log record
	 *
	 * Saves a message to the logs collection. Take the message and an optional tag.
	 *
	 * @param string $message The message to be saved to the logs.
	 *
	 * @param string $application_tag An optional tag to filter the logs.
	 *
	 * @return boolean
	 */
	public function writeLog($message, $application_tag = 'Undefined')
	{
		try {
			$doc = [
				'timestamp' => time(),
				'application' => $application_tag,
				'message' => $message,
			];
			$this->collection->insertOne($doc);
			return true;
		} catch (Exception $e) {
			trigger_error($e->getMessage(), E_USER_ERROR);
			return false;
		}
	}
}
