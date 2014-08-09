<?php
$news = News::select_line_by_id(Reg::news_id());

Reg::title($news['Title']);
Reg::meta_title($news['Title']. ". Новости");
Reg::meta_description(ZN_Seo::meta_description_prepare($news['Anons']));
Reg::meta_keywords($news['Tags']);

Reg::path
([
	"Новости [/новости]",
	"{$news['Title']} [/новости/{$news['Url']}]"
]);
?>