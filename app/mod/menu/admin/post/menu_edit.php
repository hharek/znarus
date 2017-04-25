<?php
$menu = Menu::edit($_GET['id'], $_POST);

mess_ok("Меню «{$menu['Name']}» отредактировано.");
?>