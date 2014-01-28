<?php
/* Текущая страница */
$page = Page::select_line_by_id($_GET['id']);

/* Страницы */
$page_all = Page::select_list_child_by_parent(0, $page['ID']);

/* Шаблоны */
$html = ZN_Html::select_list();

/* Заголовок и путь */
title("Редактировать страницу «{$page['Name']}»");
path
([
	"Страницы [#page/list]",
	"{$page['Name']} [#page/edit?id={$page['ID']}]"
]);
?>