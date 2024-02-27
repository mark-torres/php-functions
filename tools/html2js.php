<?php

if(!empty($_POST))
{
	$js_code = "var htmlCode = '';\n";
	$html_code = isset($_POST['code']) ? $_POST['code'] : false;
	if(!empty($html_code))
	{
		$lines = preg_split("/\n/", $html_code);
		$lline = array_pop($lines);
		$lline = preg_replace("/'/", "\\'", trim($lline));
		$lline = "\t'$lline';\n";
		foreach($lines as $i => $line)
		{
			$line = trim($line);
			$line = preg_replace("/'/", "\\'", $line);
			$lines[$i] = "\t'$line\\n'+";
		}
		$fline = array_shift($lines);
		$fline = "var htmlCode = $fline";
		array_unshift($lines, $fline);
		array_push($lines, $lline);
		$js_code = implode("\n", $lines);
	}
	die($js_code);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>HTML to Javascript string</title>
		<link rel="stylesheet" href="../css/normalize.css" type="text/css" media="screen" charset="utf-8">
	</head>
	<body style="margin: 10px 30px">
		<table>
			<tr>
				<td>
					HTML code here:<br>
					<textarea id="htmlSource" style="width: 640px; height: 300px; font: 11px monospace;"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type='button' name='Submit' value='Get JS' id='Submit' onclick="loadXMLDoc();">
				</td>
			</tr>
			<tr>
				<td>
					<textarea id="jsString" style="width: 640px; height: 300px; font: 11px monospace;" readonly="yes"></textarea>
				</td>
			</tr>
		</table>
		<script>
		function loadXMLDoc()
		{
		var xmlhttp;
		var htmlSource = document.getElementById("htmlSource");
		var htmlCode = encodeURIComponent(htmlSource.value);
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		    {
		    document.getElementById("jsString").value=xmlhttp.responseText;
		    }
		  }
		xmlhttp.open("POST","<?php echo $_SERVER['SCRIPT_NAME'] ?>",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("code="+htmlCode);
		// xmlhttp.open("GET","<?php echo $_SERVER['SCRIPT_NAME'] ?>",true);
		// xmlhttp.send();
		}
		</script>
	</body>
</html>
