<?php
$menu = Menu::get($_GET['id']);

title("Редактировать меню «{$menu['Name']}».");
path
([
	"Меню [#menu/menu]",
	$menu['Name']
]);
?>