<?php
$articles = Articles::delete($_GET['id']);
G::version()->delete("articles/" . $articles['ID']);

mess_ok("Статья «{$articles['Title']}» от «{$articles['Date']}» удалена");
redirect("#articles/list?year=" . date("Y", strtotime($articles['Date'])));
?>