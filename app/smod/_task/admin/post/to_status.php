<?php
/* Права */
_Task::is_to($_GET['id'], G::user()['ID']);

/* Задание */
$task = _Task::get($_GET['id']);

/* Сменить статус */
_Task::set_status($_GET['id'], $_POST['Note'], $_POST['Status']);

if($_POST['Send'] === "1")
{
	_Task::send_from_status($_GET['id']);
}

if($_POST['Status'] === "done")
{
	mess_ok("Задание «{$task['Name']}» выполнено.");
	redirect("#_task/to#tab_done");
}
elseif($_POST['Status'] === "fail")
{
	mess_ok("Отказано в выполнении задания «{$task['Name']}» .");
	redirect("#_task/to#tab_fail");
}
?>