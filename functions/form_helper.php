<?php

function input_time12hrs($var_name, $value = false, $options = false){
	//
	$html = "";
	if(!empty($var_name)){
		$js_var = ucwords($var_name);
		$h = "12";
		$m = "00";
		$apm = "am";
		if(!empty($value) && preg_match("/^([\d]{1,2}):([\d]{2})([ap]m)/", $value, $matches)){
			$h = $matches[1];
			$m = $matches[2];
			$apm = $matches[3];
		}
		$js_id = strtolower($var_name);
		// hour select
		$h_id = $js_id."_hour";
		$h_opts = array();
		for($i = 1; $i <= 12; $i++){
			$sel = ($i == (int)$h) ? "selected" : "" ;
			$h_opts[] = sprintf('<option %s value="%d">%d</option>', $sel, $i, $i);
		}
		$h_sel = sprintf('<select onchange="time'.$js_var.'[0] = this.options[this.selectedIndex].value;updateTime'.$js_var.'()">%s</select>', implode("\n", $h_opts));
		// minute select
		$m_id = $js_id."_min";
		$m_opts = array();
		for($i = 0; $i < 60; $i += 5){
			$sel = ($i == $m) ? "selected" : "" ;
			$m_opts[] = sprintf('<option %s value="%02d">%02d</option>', $sel, $i, $i);
		}
		$m_sel = sprintf('<select onchange="time'.$js_var.'[1] = this.options[this.selectedIndex].value;updateTime'.$js_var.'()">%s</select>', implode("\n", $m_opts));
		// am-pm select
		$apm_id = $js_id."_apm";
		$apm_opts = array(
			sprintf('<option %s value="am">am</option>', (($apm == "am") ? "selected" : "")),
			sprintf('<option %s value="pm">pm</option>', (($apm == "pm") ? "selected" : "")),
		);
		$apm_sel = sprintf('<select onchange="time'.$js_var.'[2] = this.options[this.selectedIndex].value;updateTime'.$js_var.'()">%s</select>', implode("\n", $apm_opts));
		// hidden field
		$hidden = '<input type="hidden" id="'.$js_id.'" value=""/>';
		$script = '<script type="text/javascript">'.
			sprintf('var time%s = ["%d","%02d","%s"];',$js_var,$h,$m,$apm).
			'var input'.$js_var.' = document.getElementById("'.$js_id.'");'.
			'function updateTime'.$js_var.'(){'.
				'input'.$js_var.'.value = time'.$js_var.'[0]+":"+time'.$js_var.'[1]+time'.$js_var.'[2];'.
			'};'.
			'updateTime'.$js_var.'();'.
			'</script>';
		$html = $hidden.$h_sel.$m_sel.$apm_sel.$script;
	}
	return $html;
}

function input_time24hrs($var_name, $value = false, $options = false){
	//
	$html = "";
	if(!empty($var_name)){
		$js_var = ucwords($var_name);
		$h = "00";
		$m = "00";
		if(!empty($value) && preg_match("/^([\d]{2}):([\d]{2})/", $value, $matches)){
			$h = $matches[1];
			$m = $matches[2];
		}
		$js_id = strtolower($var_name);
		// hour select
		$h_id = $js_id."_hour";
		$h_opts = array();
		for($i = 0; $i <= 23; $i++){
			$sel = ($i == (int)$h) ? "selected" : "" ;
			$h_opts[] = sprintf('<option %s value="%02d">%02d</option>', $sel, $i, $i);
		}
		$h_sel = sprintf('<select onchange="time'.$js_var.'[0] = this.options[this.selectedIndex].value;updateTime'.$js_var.'()">%s</select>', implode("\n", $h_opts));
		// minute select
		$m_id = $js_id."_min";
		$m_opts = array();
		for($i = 0; $i < 60; $i += 5){
			$sel = ($i == $m) ? "selected" : "" ;
			$m_opts[] = sprintf('<option %s value="%02d">%02d</option>', $sel, $i, $i);
		}
		$m_sel = sprintf('<select onchange="time'.$js_var.'[1] = this.options[this.selectedIndex].value;updateTime'.$js_var.'()">%s</select>', implode("\n", $m_opts));
		// hidden field
		$hidden = '<input type="hidden" id="'.$js_id.'" value=""/>';
		$script = '<script type="text/javascript">'.
			sprintf('var time%s = ["%02d","%02d"];',$js_var,$h,$m).
			'var input'.$js_var.' = document.getElementById("'.$js_id.'");'.
			'function updateTime'.$js_var.'(){'.
				'input'.$js_var.'.value = time'.$js_var.'[0]+":"+time'.$js_var.'[1]+":00";'.
			'};'.
			'updateTime'.$js_var.'();'.
			'</script>';
		$html = $hidden.$h_sel.$m_sel." hrs.".$script;
	}
	return $html;
}

function select_array($var_name, $sel_data, $value = false, $opts = false)
{
	$html = "";
	if(is_array($sel_data))
	{
		$opts = array();
		$opts[] = "<option value=''> - </option>";
		foreach($sel_data as $k => $v)
		{
			$sel = ($v == $value) ? "selected" : "";
			$opts[] = "<option $sel value='$k'>$v</option>";
		}
		$html = "<select name='$var_name'>\n".implode("\n", $opts)."</select>\n";
	}
	return $html;
} // - - end of select_array - - - - -

function select_date($var_name, $value = false, $opts = false)
{
	$html = "";
	$modes = array("normal", "past", "future");
	$mode = empty($opts['mode']) ? $modes[0] : $opts['mode'];
	if(!in_array($mode, $modes)) $mode = $modes[0];
	$years = empty($opts['years']) ? 20 : abs((int)$opts['years']);
	if(!empty($var_name))
	{
		$js_var = ucwords($var_name);
		$y = date("Y");
		$m = date("m");
		$d = date("d");
		if(!empty($value) && preg_match("/^([\d]{4})-([\d]{2})-([\d]{2})$/", $value, $matches))
		{
			$y = $matches[1];
			$m = $matches[2];
			$d = $matches[3];
		}
		$js_id = strtolower($var_name);
		// year select
		$y_opts = array();
		switch($mode)
		{
			case "future":
				$y_min = date("Y");
				$y_max = date("Y", strtotime("+$years years"));
				break;
			case "past":
				$y_min = date("Y", strtotime("-$years years"));
				$y_max = date("Y");
				break;
			default:
				$years = (int)floor($years/2);
				$y_min = date("Y", strtotime("-$years years"));
				$y_max = date("Y", strtotime("+$years years"));
				break;
		}
		for($i = $y_min; $i <= $y_max; $i++)
		{
			$sel = ($i == $y) ? "selected" : "" ;
			$y_opts[] = sprintf('<option %s value="%02d">%02d</option>', $sel, $i, $i);
		}
		$y_sel = sprintf('<select onchange="date'.$js_var.'[0] = this.options[this.selectedIndex].value;updateDate'.$js_var.'()">%s</select>', implode("\n", $y_opts));
		// month select
		$m_opts = array();
		for($i = 1; $i <= 12; $i++)
		{
			$sel = ($i == $m) ? "selected" : "" ;
			$m_opts[] = sprintf('<option %s value="%02d">%s</option>', $sel, $i, date("F", strtotime("2015-$i-20")));
		}
		$m_sel = sprintf('<select onchange="date'.$js_var.'[1] = this.options[this.selectedIndex].value;updateDate'.$js_var.'()">%s</select>', implode("\n", $m_opts));
		// day select
		$d_opts = array();
		for($i = 1; $i <= 31; $i++)
		{
			$sel = ($i == $d) ? "selected" : "" ;
			$d_opts[] = sprintf('<option %s value="%02d">%s</option>', $sel, $i, $i);
		}
		$d_sel = sprintf('<select onchange="date'.$js_var.'[2] = this.options[this.selectedIndex].value;updateDate'.$js_var.'()">%s</select>', implode("\n", $d_opts));
		// hidden field
		$hidden = '<input type="hidden" id="'.$js_id.'" value=""/>';
		$script = '<script type="text/javascript">'.
			sprintf('var date%s = ["%04d","%02d","%02d"];',$js_var,$y,$m,$d).
			'var input'.$js_var.' = document.getElementById("'.$js_id.'");'.
			'function updateDate'.$js_var.'(){'.
				'input'.$js_var.'.value = date'.$js_var.'[0]+"-"+date'.$js_var.'[1]+"-"+date'.$js_var.'[2];'.
			'};'.
			'updateDate'.$js_var.'();'.
			'</script>';
		$order = empty($opts['order']) ? 'myd' : $opts['order'];
		if(!preg_match("/^[ymd]{3}$/", $order)) $order = 'myd';
		$order = str_replace(array('y','m','d'), array('%1$s','%2$s','%3$s'), $order);
		// $html = $hidden.$y_sel.$m_sel.$d_sel.$script;
		$html = $hidden.sprintf($order, $y_sel, $m_sel, $d_sel).$script;
	}
	return $html;
} // - - end of select_date - - - - -

