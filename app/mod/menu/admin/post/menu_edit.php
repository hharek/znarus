<?php
$menu = Menu::edit($_GET['id'], $_POST['Name']);

mess_ok("Меню «{$menu['Name']}» отредактировано.");
?>