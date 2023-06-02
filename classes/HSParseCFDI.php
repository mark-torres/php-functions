<?php 

class HSParseCFDI
{
	private function parseElementAttributes($element)
	{
		$data = [];
		if ($element->hasAttributes()) {
			for ($i=0; $i < $element->attributes->count(); $i++) { 
				$att_nam = $element->attributes->item($i)->nodeName;
				$data[$att_nam] = $element->getAttribute($att_nam);
			}
		}
		return $data;
	}

	private function parseNominaEmisor(&$xpath)
	{
		$emisor = [];
		$dat1 = [];
		$dat2 = [];
		$res = $xpath->query('//cfdi:Emisor[1]');
		if ($res->length > 0) {
			$dat1 = $this->parseElementAttributes($res->item(0));
		}
		$res = $xpath->query('//nomina12:Nomina/nomina12:Emisor[1]');
		if ($res->length > 0) {
			$dat2 = $this->parseElementAttributes($res->item(0));
		}
		$emisor = array_merge($dat1, $dat2);
		return $emisor;
	}

	private function parseNominaReceptor(&$xpath)
	{
		$receptor = [];
		$dat1 = [];
		$dat2 = [];
		$res = $xpath->query('//cfdi:Receptor[1]');
		if ($res->length > 0) {
			$dat1 = $this->parseElementAttributes($res->item(0));
		}
		$res = $xpath->query('//nomina12:Nomina/nomina12:Receptor[1]');
		if ($res->length > 0) {
			$dat2 = $this->parseElementAttributes($res->item(0));
		}
		$receptor = array_merge($dat1, $dat2);
		return $receptor;
	}

	private function parseNominaGenerales(&$xpath)
	{
		$data = [];
		$res = $xpath->query('//nomina12:Nomina[1]');
		if ($res->length > 0) {
			$data = $this->parseElementAttributes($res->item(0));
		}
		return $data;
	}

	private function parseNominaPercepciones(&$xpath)
	{
		$data = [];
		$res = $xpath->query('//nomina12:Nomina/nomina12:Percepciones[1]');
		if ($res->length > 0) {
			$elem = $res->item(0);
			$data = $this->parseElementAttributes($elem);
			if ($elem->hasChildNodes()) {
				$data['list'] = [];
				$list = $xpath->query('//nomina12:Percepcion', $elem);
				foreach ($list as $e) {
					$data['list'][] = $this->parseElementAttributes($e);
				}
			}
		}
		return $data;
	}

	private function parseNominaDeducciones(&$xpath)
	{
		$data = [];
		$res = $xpath->query('//nomina12:Nomina/nomina12:Deducciones[1]');
		if ($res->length > 0) {
			$elem = $res->item(0);
			$data = $this->parseElementAttributes($elem);
			if ($elem->hasChildNodes()) {
				$data['list'] = [];
				$list = $xpath->query('//nomina12:Deduccion', $elem);
				foreach ($list as $e) {
					$data['list'][] = $this->parseElementAttributes($e);
				}
			}
		}
		return $data;
	}

	private function parseNominaOtros(&$xpath)
	{
		$data = [];
		$res = $xpath->query('//nomina12:Nomina/nomina12:OtrosPagos[1]');
		if ($res->length > 0) {
			$elem = $res->item(0);
			$data = $this->parseElementAttributes($elem);
			if ($elem->hasChildNodes()) {
				$data['list'] = [];
				$list = $xpath->query('//nomina12:OtroPago', $elem);
				foreach ($list as $e) {
					$data['list'][] = $this->parseElementAttributes($e);
				}
			}
		}
		return $data;
	}

	private function loadXMLFile($file_path)
	{
		$xml = file_get_contents($file_path);
		$doc = @DOMDocument::loadXML($xml);
		// $alt_encod = mb_list_encodings();
		$alt_encod = [
			'ISO-8859-1',
			'Windows-1252',
		];
		if ($doc === false) {
			// check file encoding in unix:
			// file -i archivo.xml
			foreach ($alt_encod as $enc) {
				$xml_enc = mb_convert_encoding($xml, 'UTF-8', $enc);
				$doc = @DOMDocument::loadXML($xml_enc);
				if ($doc !== false) {
					break;
				}
			}
		}
		if ($doc === false) {
			return false;
		}
		return $doc;
	}

	function parseNominaV12($file_path)
	{
		$data = [];
		$doc = $this->loadXMLFile($file_path);
		if ($doc === false) {
			echo "Error loading XML file\n";
			return false;
		}
		$xpath = new DOMXPath($doc);
		// buscar datos generales
		$data['generales'] = $this->parseNominaGenerales($xpath);
		// buscar emisor
		$data['emisor'] = $this->parseNominaEmisor($xpath);
		// buscar receptor
		$data['receptor'] = $this->parseNominaReceptor($xpath);
		// buscar percepciones
		$data['percepciones'] = $this->parseNominaPercepciones($xpath);
		// buscar deducciones
		$data['deducciones'] = $this->parseNominaDeducciones($xpath);
		// buscar otros pagos
		$data['otros_pagos'] = $this->parseNominaOtros($xpath);
		return $data;
	}
}
