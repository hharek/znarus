<?php
$task = _Task::delete($_GET['id']);

mess_ok("Задание «{$task['Name']}» удалено.");
redirect("#_task/list");
?>