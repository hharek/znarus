<?php
$page = _Page::edit($_POST, $_GET['id']);

mess_ok("Мета данные страницы «{$page['Name']}» изменены.");
?>