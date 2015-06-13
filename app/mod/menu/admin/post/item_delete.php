<?php
$item = Menu_Item::delete($_GET['id']);
$menu = Menu::get($item['Menu_ID']);

mess_ok("Пункт «{$item['Name']}» из меню «{$menu['Name']}» удалён.");
redirect("#menu/item?menu_id={$menu['ID']}");
?>