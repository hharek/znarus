<?php
$date_require = "";
if(isset($_POST['Date_Require_Select']))
{
	$date_require = $_POST['Date_Require_Date'] . " " . $_POST['Date_Require_Time'] . ":00";
}

$task = _Task::add
(
	G::user()['ID'], 
	$_POST['To'], 
	$_POST['Name'], 
	$_POST['Content'], 
	"",
	$date_require
);

if($_POST['Send'] === "1")
{
	_Task::send_to($task['ID']);
}

mess_ok("Поручение «{$task['Name']}» создано.");
redirect("#_task/from");
?>