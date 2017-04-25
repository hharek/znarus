<?php
$html = _Html::get_all();

title("Добавить страницу");
path
([
	"Страницы [#_page/list]",
	"Добавить"
]);

packjs("editor", ["name" => "Content"]);

draft("page/add");
?>