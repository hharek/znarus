<?php
$news = News::delete($_GET['id']);

G::version()->delete("news/" . $news['ID']);

mess_ok("Новость «{$news['Title']}» от «{$news['Date']}» удалена");
redirect("#news/list?year=" . date("Y", strtotime($news['Date'])));
?>