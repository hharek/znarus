<?php
title("Добавить статью");
path
([
	"Статьи [#articles/list]",
	"Добавить"
]);
draft("articles/add");

packjs("editor", ["name" => "Content"]);
packjs("calendar", ["name" => "Date"]);
?>