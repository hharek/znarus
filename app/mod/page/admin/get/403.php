<?php
title("Страница 403");
path
([
	"Другие страницы [#page/other]",
	"Страница 403"
]);

version("page/page_403");

packjs("editor", ["name" => "Content"]);

$html = _Html::get_all();
?>