<?php
_Task::is_from($_GET['id'], G::user()['ID']);

$task = _Task::delete($_GET['id']);

mess_ok("Задание «{$task['Name']}» удалено.");
redirect("#_task/from");
?>