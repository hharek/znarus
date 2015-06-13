<?php
/* Новости */
$news = News::get(G::news_id());

/* Мета */
title($news['Title']);
meta_title($news['Title']. ". Новости");
meta_description($news['Anons']);
tags($news['Tags']);
path
([
	"Новости [/новости]",
	"{$news['Title']} [/новости/{$news['Url']}]"
]);
last_modified($news['Last_Modified']);
?>