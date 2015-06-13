<?php
/* Права */
_Task::is_to($_GET['id'], G::user()['ID']);

/* Задание */
$task = _Task::get($_GET['id']);

/* Тип */
$status = "done";
if(isset($_GET['status']))
{
	if(!in_array($_GET['status'], ['done','fail']))
	{
		throw new Exception("Статус задан неверно.");
	}
	$status = $_GET['status'];
}

/* Заголовок */
title("Статус задания «{$task['Name']}».");
path
([
	"Мои задания [#_task/to]",
	"{$task['Name']} [#_task/to_view?id={$task['ID']}]",
	"Сменить статус"
]);
?>