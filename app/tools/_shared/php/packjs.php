<?php
header("Content-Type: application/x-javascript; charset=utf-8");

$packjs = _Packjs::get_all();

foreach ($packjs as $val)
{
	echo "/*----------------------- " . $val['Identified'] . " ------------------------*/\n";
	echo file_get_contents(DIR_TOOLS . "/_packjs/" . $val['Identified'] . "/.packjs.js");
	echo "\n\n";
}
?>