<?php
_Task::is_from($_GET['id'], G::user()['ID']);

$task_old = _Task::get($_GET['id']);

if($task_old['Status'] === "create")
{
	$date_require = "";
	if(isset($_POST['Date_Require_Select']))
	{
		$date_require = $_POST['Date_Require_Date'] . " " . $_POST['Date_Require_Time'] . ":00";
	}
}
else
{
	$date_require = $task_old['Date_Require'];
}

$task = _Task::edit
(
	$_GET['id'],
	$task_old['From'],
	$_POST['To'],
	$_POST['Name'], 
	$_POST['Content'], 
	$task_old['Note'],
	$task_old['Status'],
	$task_old['Date_Create'],
	$date_require,
	$task_old['Date_Done'],
	$task_old['Date_Fail']
);

mess_ok("Задание «{$task['Name']}» отредактировано.");
?>