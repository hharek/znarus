<?php
/* Поручения */
$task = _Task::get_by_from(G::user()['ID']);

/* Заголовок */
title("Мои поручения");
path
([
	"Мои поручения [#_task/from]"
]);
?>