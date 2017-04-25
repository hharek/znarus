<?php
/* Собираем все урлы */
if (P::get("_page", "url_hierarchy"))
{
	$url = _Page::url_all_hierarchy();
}
else
{
	$url = _Page::url_all();
}

/* Подбираем урлы */
$url_path = G::url_path();
foreach ($url as $u)
{
	if ($url_path === $u['url'])
	{
		if ($u['active'] === false and !isset($_GET['show']))
		{
			return false;
		}
		
		G::page_id($u['id']);
		
		return "content";
	}
}
?>