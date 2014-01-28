<?php
/* Корень */
$parent = 0;
if(isset($_GET['parent']))
{
	$parent = (int)$_GET['parent'];
	if($parent !== 0)
	{Page::is_id($parent);}
}

/* Страницы */
$page_all = Page::select_list_child_by_parent(0);

/* Шаблоны */
$html = ZN_Html::select_list();

/* Заголовок и путь */
title("Добавить страницу");
path
([
	"Страницы [#page/list]",
	"Добавить [#page/add?parent={$parent}]"
]);
?>