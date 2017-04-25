<?php
$menu = Menu::remove($_GET['id']);

mess_ok("Меню «{$menu['Name']}» удалено.");
redirect("#menu/menu");
?>