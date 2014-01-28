<?php
$page = Page::delete($_GET['id']);

mess_ok("Страница «{$page['Name']}» удалёна.");
redirect("#page/list");
?>