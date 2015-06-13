<?php
/* Права */
_Task::is_to($_GET['id'], G::user()['ID']);

/* Задание */
$task = _Task::get($_GET['id']);

/* Заголовок */
title("Просмотр задания «{$task['Name']}».");
path
([
	"Мои задания [#_task/to]",
	"{$task['Name']} [#_task/to_view?id={$task['ID']}]"
]);
?>