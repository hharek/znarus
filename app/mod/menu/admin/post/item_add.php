<?php
/* Меню */
$menu = Menu::get($_GET['menu_id']);

/* Добавить */
if (empty($_POST['Parent']))
{
	$_POST['Parent'] = null;
}
$item = Menu_Item::add($_POST);

/* Сообщение */
mess_ok("Пункт «{$item['Name']}» добавлен в меню «{$menu['ID']}»");
redirect("#menu/item?menu_id={$menu['ID']}");
?>