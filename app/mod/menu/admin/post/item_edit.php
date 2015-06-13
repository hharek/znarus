<?php
$item = Menu_Item::edit($_GET['id'], $_POST['Name'], $_POST['Url'], $_POST['Parent']);
$menu = Menu::get($item['Menu_ID']);

mess_ok("Пункт «{$item['Name']}» в меню «{$menu['Name']}» отредактирован.");
?>