<?php

// =======================================================
// = Encode array into XML: array_to_xml & _array_to_xml =
// =======================================================
function array_to_xml($data)
{	
	$xml = new DOMDocument("1.0");
	$root = $xml->createElement("data");
	$xml->appendChild($root);
	if(is_array($data))
	{
		foreach ($data as $key => $value)
		{
			$child = _array_to_xml($xml, $key, $value);
			if(!empty($child))
				$root->appendChild($child);
		}
	}
	return $xml->saveXML();
}// end of 'array_to_xml' function -------------------------

function _array_to_xml(&$xml, $dkey, $dvalue)
{
	$child = false;
	// case 1: "key" => array()
	if(!preg_match("/^\d+$/", $dkey) && is_array($dvalue))
	{
		$child = $xml->createElement($dkey);
		foreach ($dvalue as $key => $value)
		{
			$ch = _array_to_xml($xml, $key, $value);
			if(!empty($ch)) $child->appendChild($ch);
		}
	}
	// case 2: # => array()
	if(preg_match("/^\d+$/", $dkey) && is_array($dvalue))
	{
		$child = $xml->createElement("element");
		foreach ($dvalue as $key => $value)
		{
			$ch = _array_to_xml($xml, $key, $value);
			if(!empty($ch)) $child->appendChild($ch);
		}
	}
	// case 3: "key" => "value"
	if(!preg_match("/^\d+$/", $dkey) && !is_array($dvalue))
	{
		$child = $xml->createElement($dkey);
		$childText = $xml->createTextNode($dvalue);
		$child->appendChild($childText);
	}
	// case 4: # => "value"
	if(preg_match("/^\d+$/", $dkey) && !is_array($dvalue))
	{
		$child = $xml->createElement("value");
		$childText = $xml->createTextNode($dvalue);
		$child->appendChild($childText);
	}
	return $child;
}// end of '_array_to_xml' function -------------------------

