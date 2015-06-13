<?php
/* Текущий пункт */
$item = Menu_Item::get($_GET['id']);

/* Меню */
$menu = Menu::get($item['Menu_ID']);

/* Все пункты */
$item_all = Menu_Item::get_child_by_parent($menu['ID'], 0, $item['ID']);

/* Заголовок */
title("Редактировать пункт «{$item['Name']}» в меню «{$menu['Name']}»");
path
([
	"Меню [#menu/item]",
	"{$menu['Name']} [#menu/item?menu_id={$menu['ID']}]",
	"{$item['Name']}"
]);
?>