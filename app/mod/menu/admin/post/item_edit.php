<?php
if (empty($_POST['Parent']))
{
	$_POST['Parent'] = null;
}

$item = Menu_Item::edit($_GET['id'], $_POST);
$menu = Menu::get($item['Menu_ID']);

mess_ok("Пункт «{$item['Name']}» в меню «{$menu['Name']}» отредактирован.");
?>