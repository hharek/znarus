<?php
/* Текущая страница */
$page = Page::get($_GET['id']);

/* Страницы */
$page_all = Page::get_child_by_parent(0, $page['ID']);

/* Шаблоны */
$html = _Html::get_all();

/* Версионность и автосохранение */
version("page/{$page['ID']}");
autosave();

/* Заголовок и путь */
title("Редактировать страницу «{$page['Name']}»");
path
([
	"Страницы [#page/list]",
	$page['Name']
]);

/* Пакеты JS */
packjs("editor", ["name" => "Content"]);
?>