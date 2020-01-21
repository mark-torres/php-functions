<?php

// requires MongoDB Driver (PHPLIB)
require 'vendor/autoload.php';

require_once '../classes/HSMongoLogger.php';

$logger = new HSMongoLogger('Test01');
$logger->connect();
$logger->writeLog('Hola mundo!');
