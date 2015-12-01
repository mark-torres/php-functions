<?php

function get_hash($data, $algo) {
	$algos = hash_algos();
	$data_hash = '';
	if (in_array($algo, $algos) && !empty($data)) {
		$data_string = serialize($data);
		$data_hash = hash($algo, $data_string);
	}
	return $data_hash;
}

