<?php
/**
* MultiCache: Store to APC, MongoDB and files
*/
class HSMultiCache
{
	// flags
	private $useAPC = false;
	private $useMongo = false;
	private $useFiles = false;
	// status
	private $statusAPC = "";
	private $statusMongo = "";
	private $statusFiles = "";
	// handlers
	private $mongo = false;
	private $mongoCollection = false;

	// ===============
	// = CONSTRUCTOR =
	// ===============
	function __construct($filesDir, $options = array()) {
		if (!is_array($options)) {
			$options = array();
		}
		// check files status
		if (is_writable($filesDir)) {
			$this->statusFiles = "Directory '$filesDir' is writable";
			$this->useFiles = true;
		}
		// check mongo status
		if (class_exists('MongoClient')) {
			try {
				$this->mongo = new MongoClient();
				$this->useMongo = true;
				$this->statusMongo = "MongoDB enabled";
				// Use DB
				$my_db = $mongo->selectDB("multicache");
				// create collection if not present
				$name = 'cache_v1';
				$collections = $my_db->getCollectionNames();
				// 200 MB as default max size
				$mongo_max_size = 1024 * 1024 * 200;
				if (!empty($options['mongo_max_size']) && is_numeric($options['mongo_max_size'])) {
					$mongo_max_size = $options['mongo_max_size'];
				}
				if (!in_array($name, $collections)) {
					$this->mongoCollection = $my_db->createCollection($name, array(
						'capped'      => true,
						'size'        => $mongo_max_size,
						// 'max'         => 50000,
						'autoIndexId' => true,
					));
					// add index for value
					$this->mongoCollection->ensureIndex(array('key' => 1));
				} else {
					$this->mongoCollection = $my_db->selectCollection($name);
				}
			} catch (Exception $e) {
				$this->statusMongo = $e->getMessage();
			}
		} else {
			$this->statusMongo = "MongoDB extension is not installed.";
		}
		// check APC status
		if (function_exists("apc_cache_info")) {
			try {
				$info = apc_cache_info("user", true);
				$this->useAPC = true;
				$this->statusAPC = "APC enabled";
			} catch (Exception $e) {
				$this->statusAPC = "APC is not enabled";
			}
		} else {
			$this->statusAPC = "Extension is not installed";
		}

	}

	// =============
	// = SAVE DATA =
	// =============
	public function store($key, $data, $ttl = 600) {
		# code...
	}
	// ============
	// = GET DATA =
	// ============
	public function fetch($key) {
		# code...
	}
	// ===============
	// = DELETE DATA =
	// ===============
	public function delete($key) {
		# code...
	}
}
