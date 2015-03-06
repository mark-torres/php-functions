<?php
/**
* HSoft Array to XML by Mark Torres <mark.torres.mets@gmail.com>
*/
class HSArrayToXML
{
	private $source; // source array
	private $dom; // local DOMDocument
	function __construct($data)
	{
		$this->source = $data;
	}
	
	public function getXML()
	{
		$this->dom = new DOMDocument("1.0");
		$root = $this->dom->createElement("xml");
		$this->dom->appendChild($root);
		if(is_array($this->source))
		{
			foreach ($this->source as $key => $value)
			{
				$child = $this->arrayToXML($key, $value);
				if(!empty($child))
					$root->appendChild($child);
			}
		}
		return $this->dom->saveXML();
	}// end of 'getXML' function -------------------------
	
	private function arrayToXML($dkey, $dvalue)
	{
		$child = false;
		// case 1: "key" => array()
		if(!preg_match("/^\d+$/", $dkey) && is_array($dvalue))
		{
			$child = $this->dom->createElement($dkey);
			foreach ($dvalue as $key => $value)
			{
				$ch = $this->arrayToXML($key, $value);
				if(!empty($ch)) $child->appendChild($ch);
			}
		}
		// case 2: # => array()
		if(preg_match("/^\d+$/", $dkey) && is_array($dvalue))
		{
			$child = $this->dom->createElement("element");
			foreach ($dvalue as $key => $value)
			{
				$ch = $this->arrayToXML($key, $value);
				if(!empty($ch)) $child->appendChild($ch);
			}
		}
		// case 3: "key" => "value"
		if(!preg_match("/^\d+$/", $dkey) && !is_array($dvalue))
		{
			$child = $this->dom->createElement($dkey);
			$childText = $this->dom->createTextNode($dvalue);
			$child->appendChild($childText);
		}
		// case 4: # => "value"
		if(preg_match("/^\d+$/", $dkey) && !is_array($dvalue))
		{
			$child = $this->dom->createElement("value");
			$childText = $this->dom->createTextNode($dvalue);
			$child->appendChild($childText);
		}
		return $child;
	}// end of 'arrayToXML' function -------------------------
	
}// end of class HSArrayToXML = = = = = = = = = = = = = = = =
