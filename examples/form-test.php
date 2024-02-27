<?php
include '../functions/form_helper.php';

$sel_data = array('aenean','lacinia','bibendum','nulla','sed','consectetur','vestibulum','id','ligula','porta');
$sel_val = "bibendum";

$today = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Text test</title>
	<meta name="author" content="Mark Torres">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	
	<table>

		<tr>
			<td>
				Time select 12 hrs:
			</td>
			<td><?php echo input_time12hrs("time12", "3:20pm") ?>&nbsp;</td>
		</tr>

		<tr>
			<td>
				Time select 24 hrs:
			</td>
			<td><?php echo input_time24hrs("time24", "15:30") ?>&nbsp;</td>
		</tr>

		<tr>
			<td>
				Select from array:
			</td>
			<td><?php echo select_array("select1",$sel_data, $sel_val) ?>&nbsp;</td>
		</tr>

		<tr>
			<td>
				Date select (normal, 20 years):
			</td>
			<td><?php echo select_date("select2", $today, array('order'=>'dmy')) ?>&nbsp;</td>
		</tr>

		<tr>
			<td>
				Date select (future, 5 years):
			</td>
			<td><?php echo select_date("select3", $today, array('mode'=>'future', 'years'=>5)) ?>&nbsp;</td>
		</tr>

		<tr>
			<td>
				Date select (past, 50 years):
			</td>
			<td><?php echo select_date("select4", $today, array('mode'=>'past', 'years'=>50)) ?>&nbsp;</td>
		</tr>

	</table>
</body>
</html>

