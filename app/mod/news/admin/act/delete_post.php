<?php
$news = News::delete($_GET['id']);

mess_ok("Новость «{$news['Title']}» от «{$news['Date']}» удалена");
redirect("#news/list?year=" . date("Y", strtotime($news['Date'])));
?>