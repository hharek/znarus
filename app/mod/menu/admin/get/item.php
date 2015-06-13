<?php
/* Меню */
$menu_all = Menu::get_all();

/* Текущее меню */
$menu = [];
if (!empty($_GET['menu_id']))
{
	$menu = Menu::get($_GET['menu_id']);
}

/* Пункты меню */
$item = [];
if (!empty($menu))
{
	$item = Menu_Item::get_child_by_parent($menu['ID'], 0);
}

/* Заголовок и путь */
title("Меню");
path(["Меню"]);

if (!empty($menu))
{
	title("Меню «{$menu['Name']}»");
	path
	([
		"Меню [#menu/item]",
		$menu['Name']
	]);
}
?>