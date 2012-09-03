<?php
$pack = ZN_Pack::select_line_by_id($_GET['pack_id']);
$fdata = array
(
	"name" => "",
	"identified" => "",
	"desc" => ""
);
?>
