<?php
/* Заголовок */
title("Добавить задание");
path
([
	"Задания [#_task/list]",
	"Добавить [#_task/add]"
]);

/* Пользователи */
$user = _User::get_all();

/* Календарь */
packjs("datepicker", ["name" => "Date_Require_Date"]);
?>