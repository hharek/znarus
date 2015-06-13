<?php
/* Меню */
$menu = Menu::get($_GET['menu_id']);

/* Добавить */
$item = Menu_Item::add($_POST['Name'], $_POST['Url'], $_POST['Parent'], $_GET['menu_id']);

/* Сообщение */
mess_ok("Пункт «{$item['Name']}» добавлен в меню «{$menu['ID']}»");
redirect("#menu/item?menu_id={$menu['ID']}");
?>