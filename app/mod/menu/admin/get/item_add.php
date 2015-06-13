<?php
/* Меню */
$menu = Menu::get($_GET['menu_id']);

/* Пункты меню */
$item_all = Menu_Item::get_child_by_parent($menu['ID'], 0);

title("Добавить пункт в меню «{$menu['Name']}»");
path
([
	"Меню [#menu/item]",
	"{$menu['Name']} [#menu/item?menu_id={$menu['ID']}]",
	"Добавить пункт"
]);
?>