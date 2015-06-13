<?php
$task = _Task::get($_GET['id']);
$user = _User::get_all();

title("Редактировать задание «{$task['Name']}»");
path
([
	"Задания [#_task/list]",
	"{$task['Name']} [#_task/edit?id={$task['ID']}]"
]);
	
packjs("datepicker", ["name" => "Date_Create_Date"]);
packjs("datepicker", ["name" => "Date_Require_Date"]);
packjs("datepicker", ["name" => "Date_Done_Date"]);
packjs("datepicker", ["name" => "Date_Fail_Date"]);
?>