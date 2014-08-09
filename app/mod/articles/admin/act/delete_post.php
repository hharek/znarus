<?php
$articles = Articles::delete($_GET['id']);

mess_ok("Новость «{$articles['Title']}» от «{$articles['Date']}» удалена");
redirect("#articles/list?year=" . date("Y", strtotime($articles['Date'])));
?>