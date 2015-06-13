<?php
/* Статья */
$articles = Articles::get(G::articles_id());

/* Мета */
title($articles['Title']);
meta_title($articles['Title']. ". Статьи");
meta_description($articles['Anons']);
tags($articles['Tags']);
path
([
	"Полезные статьи [/статьи]",
	"{$articles['Title']} [/статьи/{$articles['Url']}]"
]);
last_modified($articles['Last_Modified']);
?>