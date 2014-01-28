<?php
/* Текущий пункт */
$item = Menu_Item::select_line_by_id($_GET['id']);

/* Меню */
$menu = Menu::select_line_by_id($item['Menu_ID']);

/* Все пункты */
$item_all = Menu_Item::select_list_child_by_parent($menu['ID'], 0, $item['ID']);

/* Заголовок */
title("Редактировать пункт «{$item['Name']}» в меню «{$menu['Name']}»");
path
([
	"{$menu['Name']} [#menu/item?menu_id={$menu['ID']}]",
	"{$item['Name']} [#menu/item_edit?id={$item['ID']}]"
]);
?>