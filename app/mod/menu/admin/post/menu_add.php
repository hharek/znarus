<?php
$menu = Menu::add($_POST);

mess_ok("Меню «{$menu['Name']}» добавлено.");
redirect("#menu/menu");
?>