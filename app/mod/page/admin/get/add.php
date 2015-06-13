<?php
/* Корень */
$parent = 0;
if (isset($_GET['parent']))
{
	$parent = (int) $_GET['parent'];
	if ($parent !== 0)
	{
		Page::is($parent);
	}
}

/* Страницы */
$page_all = Page::get_child_by_parent(0);

/* Шаблоны */
$html = _Html::get_all();

/* Черновик */
draft("page/add");

/* Заголовок и путь */
title("Добавить страницу");
path
([
	"Страницы [#page/list]",
	"Добавить"
]);

/* Пакет JS */
packjs("editor", ["name" => "Content"]);
?>