<?php
$menu = Menu::add($_POST['Name']);

mess_ok("Меню «{$menu['Name']}» добавлено.");
redirect("#menu/menu");
?>