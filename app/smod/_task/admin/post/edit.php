<?php
$task = _Task::edit
(
	$_GET['id'],
	$_POST['From'],
	$_POST['To'],
	$_POST['Name'], 
	$_POST['Content'], 
	$_POST['Note'],
	$_POST['Status'],
	$_POST['Date_Create_Date'] . " " . $_POST['Date_Create_Time'],
	isset($_POST['Date_Require_Select']) ? $_POST['Date_Require_Date'] . " " . $_POST['Date_Require_Time'] . ":00" : "",
	isset($_POST['Date_Done_Select']) ? $_POST['Date_Done_Date'] . " " . $_POST['Date_Done_Time'] : "",
	isset($_POST['Date_Fail_Select']) ? $_POST['Date_Fail_Date'] . " " . $_POST['Date_Fail_Time'] : ""
);

mess_ok("Задание «{$task['Name']}» отредактировано.");
?>