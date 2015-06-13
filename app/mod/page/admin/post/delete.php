<?php
$page = Page::delete($_GET['id']);

G::version()->delete("page/" . $page['ID']);

mess_ok("Страница «{$page['Name']}» удалёна.");
redirect("#page/list");
?>