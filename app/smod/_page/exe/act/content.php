<?php
exe_in_filter(["show"]);

$page = _Page::get(G::page_id());

/* Общие данные */
title($page['Name']);
tags($page['Tags']);
if (CACHE_PAGE_ENABLE === false)
{
	last_modified($page['Last_Modified']);
}

/* Мета */
if (!empty($page['Meta_Title']))
{
	meta_title($page['Meta_Title']);
}
if (!empty($page['Meta_Description']))
{
	meta_description($page['Meta_Description']);
}
if (!empty($page['Meta_Keywords']))
{
	meta_keywords($page['Meta_Keywords']);
}

/* Путь */
$page_path = _Page::path(G::page_id());

$prefix = "";
if (P::get("_page", "url_auto"))
{
	$prefix = P::get("_page", "url_auto_prefix");
}

$path = []; $url = "";
foreach ($page_path as $p)
{
	if (P::get("_page", "url_hierarchy"))
	{
		$url .= "/" . $prefix . $p['Url'];
	}
	else
	{
		$url = "/" . $prefix . $p['Url'];
	}
	
	$path[] = "{$p['Name']}[{$url}]";
}
path($path);

?>