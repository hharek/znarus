<?php
/* Заголовок */
title("Дать поручение");
path
([
	"Мои поручения [#_task/from]",
	"Добавить [#_task/from_add]"
]);

/* Пользователи */
$user = _User::get_all();

/* Календарь */
packjs("datepicker", ["name" => "Date_Require_Date"]);
?>