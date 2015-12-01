<pre>
<?php
include '../classes/multicache.php';

$tmp_dir = sys_get_temp_dir();
$mcache = new HSMultiCache($tmp_dir);

var_dump($mcache);

?>
</pre>
