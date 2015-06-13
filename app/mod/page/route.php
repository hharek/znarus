<?php
$page = Page::get_url_all();
foreach ($page as $val)
{
	if (G::url_path() === $val['Url'])
	{
		G::page_id($val['ID']);
		return "content";
	}
}
?>