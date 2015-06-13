<?php
title("Главная страница");
path
([
	"Другие страницы [#page/other]",
	"Главная страница"
]);

version("page/page_home");

packjs("editor", ["name" => "Content"]);

$html = _Html::get_all();
?>