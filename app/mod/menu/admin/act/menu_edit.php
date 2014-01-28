<?php
$menu = Menu::select_line_by_id($_GET['id']);

title("Редактировать меню «{$menu['Name']}».");
path
([
	"Меню [#menu/menu]",
	"{$menu['Name']} [#menu/menu_edit?id={$menu['ID']}]"
]);
?>