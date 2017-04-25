<?php
$page = _Page::remove($_GET['id']);

G::version()->delete("page/" . $page['ID']);

mess_ok("Страница «{$page['Name']}» удалена.");
redirect("#_page/list");
?>