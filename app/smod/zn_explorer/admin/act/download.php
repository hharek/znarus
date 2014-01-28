<?php
if(!empty($_GET['file']))
{
	header("Content-Disposition: attachment; filename=" . basename($_GET['file']));
	header("Content-Type: application/octet-stream");
	
	echo Reg::file()->get($_GET['file']);
}

if(!empty($_GET['files']))
{
	
}
?>