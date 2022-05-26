<?php

/**
 * Forms helper class
 */
class HSFormsHelper
{
	const INPUT_TYPES = [
		"button",
		"checkbox",
		"color",
		"date",
		"datetime-local",
		"email",
		"file",
		"hidden",
		"image",
		"month",
		"number",
		"password",
		"radio",
		"range",
		"reset",
		"search",
		"submit",
		"tel",
		"text",
		"time",
		"url",
		"week",
	];
	function __construct()
	{
		// code...
	}

	public function cleanAttributes(&$attributes)
	{
		// unset type, name and value
		if (array_key_exists('type', $attributes)) {
			unset($attributes['type']);
		}
		if (array_key_exists('name', $attributes)) {
			unset($attributes['name']);
		}
		if (array_key_exists('value', $attributes)) {
			unset($attributes['value']);
		}
	}

	public function input($type, $name, $value, $attrs = false)
	{
		$html = "";
		if (!in_array($type, self::INPUT_TYPES)) {
			$type = "text";
		}
		$tag_format = '<input %s/>';
		$attr_format = '%s="%s"';
		if (!is_array($attrs)) {
			$attrs = [];
		} else {
			$this->cleanAttributes($attrs);
		}
		$attributes = [];
		// set optional attributes if not hidden type
		if ($type != 'hidden') {
			foreach ($attrs as $attr => $val) {
				$attributes[] = sprintf($attr_format, $attr, htmlentities($val));
			}
		}
		// set value
		$attributes[] = sprintf($attr_format, 'value', htmlentities($value));
		// set name
		$attributes[] = sprintf($attr_format, 'name', $name);
		// set type
		$attributes[] = sprintf($attr_format, 'type', $type);
		// reverse attributes array
		$attributes = array_reverse($attributes);
		$html = sprintf($tag_format, implode(' ', $attributes));
		return $html;
	}

	public function textarea($name, $value, $attrs = false)
	{
		$html = "";
		$tag_format = '<textarea %s>%s</textarea>';
		$attr_format = '%s="%s"';
		if (!is_array($attrs)) {
			$attrs = [];
		} else {
			$this->cleanAttributes($attrs);
		}
		$attributes = [];
		// set optional attributes
		foreach ($attrs as $attr => $val) {
			$attributes[] = sprintf($attr_format, $attr, htmlentities($val));
		}
		// set name
		$attributes[] = sprintf($attr_format, 'name', $name);
		// reverse attributes array
		$attributes = array_reverse($attributes);
		$html = sprintf($tag_format, implode(' ', $attributes), htmlentities($value));
		return $html;
	}

	public function select($name, $options, $value, $attrs = false)
	{
		$html = "";
		$tag_format = '<select %s>%s</select>';
		$grp_format = '<optgroup label="%s">%s</optgroup>';
		$opt_format = '<option %s value="%s">%s</option>';
		$attr_format = '%s="%s"';
		if (!is_array($attrs)) {
			$attrs = [];
		} else {
			$this->cleanAttributes($attrs);
		}
		$attributes = [];
		// set optional attributes
		foreach ($attrs as $attr => $val) {
			$attributes[] = sprintf($attr_format, $attr, htmlentities($val));
		}
		// set name
		$attributes[] = sprintf($attr_format, 'name', $name);
		// reverse attributes array
		$attributes = array_reverse($attributes);
		$opts_html = [
			sprintf($opt_format,'','', ' - ')
		];
		$group_options = (count(array_keys($options)) > 1);
		foreach ($options as $label => $opts) {
			$label_opts = [];
			foreach ($opts as $val => $text) {
				$sel = '';
				if ($value == $val) {
					$sel = 'selected';
				}
				$html_opt = sprintf(
					$opt_format,
					$sel,
					htmlentities($val),
					htmlentities($text)
				);
				$label_opts[] = $html_opt;
			}
			$label_opts = implode("", $label_opts);
			if ($group_options) {
				$label_opts = sprintf($grp_format, $label, $label_opts);
			}
			$opts_html[] = $label_opts;
		}
		$opts_html = implode('', $opts_html);
		$html = sprintf($tag_format, implode(' ', $attributes), $opts_html);
		return $html;
	}

	public function tidyXml($xml)
	{
		// https://api.html-tidy.org/tidy/quickref_5.8.0.html
		$config = array(
			'indent' => TRUE,
			'indent-spaces' => 1,
			'indent-with-tabs' => TRUE,
			'input-xml' => TRUE,
			'output-xml' => TRUE,
			'wrap' => 200
		);
		$tidy = new tidy;
		return $tidy->repairString($xml, $config, 'utf8');
	}
}
