<?php
$articles = Articles::select_line_by_id(Reg::articles_id());

Reg::title($articles['Title']);
Reg::meta_title($articles['Title']. ". Статьи");
Reg::meta_description(ZN_Seo::meta_description_prepare($articles['Anons']));
Reg::meta_keywords($articles['Tags']);

Reg::path
([
	"Статьи [/статьи]",
	"{$articles['Title']} [/статьи/{$articles['Url']}]"
]);
?>