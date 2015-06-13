<?php
_Task::is_from($_GET['id'], G::user()['ID']);

$task = _Task::get($_GET['id']);
$user = _User::get_all();

title("Редактировать поручение «{$task['Name']}»");
path
([
	"Поручения [#_task/from]",
	"{$task['Name']}"
]);
	
packjs("datepicker", ["name" => "Date_Require_Date"]);
?>