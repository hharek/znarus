<?php
/* Меню */
$menu = Menu::select_line_by_id($_GET['menu_id']);

/* Пункты меню */
$item_all = Menu_Item::select_list_child_by_parent($menu['ID'], 0);

title("Добавить пункт в меню «{$menu['Name']}»");
path
([
	"{$menu['Name']} [#menu/item?menu_id={$menu['ID']}]",
	"Добавить пункт [#menu/item_add?menu_id={$menu['ID']}]"
]);
?>