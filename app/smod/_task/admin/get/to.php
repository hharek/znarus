<?php
/* Задания */
$task = _Task::get_by_to(G::user()['ID']);

/* Заголовок */
title("Мои задания");
path
([
	"Мои задания [#_task/to]"
]);
?>