<?php
/* Все новости */
$news = News::get_all();

/* Мета */
title("Новости");
tags("новости, новости сайта, последние новости");
path(["Новости [/новости]"]);

/* Время последнего изменения */
if (!empty($news))
{
	$last_modified = $news[0]['Last_Modified'];
	foreach ($news as $val)
	{	
		if (strtotime($val['Last_Modified']) > strtotime($last_modified))
		{
			$last_modified = $val['Last_Modified'];
		}
	}
	
	last_modified($last_modified);
}
?>