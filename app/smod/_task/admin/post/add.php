<?php
$date_require = "";
if(isset($_POST['Date_Require_Select']))
{
	$date_require = $_POST['Date_Require_Date'] . " " . $_POST['Date_Require_Time'] . ":00";
}

$task = _Task::add
(
	$_POST['From'], 
	$_POST['To'], 
	$_POST['Name'], 
	$_POST['Content'], 
	"",
	$date_require
);

mess_ok("Задание «{$_POST['Name']}» добавлено.");
redirect("#_task/list");
?>