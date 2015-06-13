<?php
draft("news/add");

title("Добавить новость");
path
([
	"Новости [#news/list]",
	"Добавить"
]);

packjs("editor", ["name" => "Content"]);
packjs("datepicker", ["name" => "Date"]);
?>