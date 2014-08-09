<?php
$page = Page::select_line_by_id(Reg::page_id());

Reg::title($page['Name']);
Reg::meta_title($page['Name']);
Reg::meta_description(ZN_Seo::meta_description_prepare($page['Content']));
Reg::meta_keywords($page['Tags']);

Reg::path
([
	"{$page['Name']} [/{$page['Url']}]"
]);
?>