<?php
/* Все статьи */
$articles = Articles::get_all();

/* Мета */
title("Статьи");
tags("статьи, полезные статьи");
path(["Полезные статьи [/статьи]"]);

/* Время последнего изменения */
if (!empty($articles))
{
	$last_modified = $articles[0]['Last_Modified'];
	foreach ($articles as $val)
	{	
		if (strtotime($val['Last_Modified']) > strtotime($last_modified))
		{
			$last_modified = $val['Last_Modified'];
		}
	}
	
	last_modified($last_modified);
}
?>