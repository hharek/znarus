<?php
$page = _Page::get($_GET['id']);
$html = _Html::get_all();

/* Заголовок */
title("Редактировать страницу «{$page['Name']}»");
path
([
	"Страницы [#_page/list]",
	$page['Name']
]);

packjs("editor", ["name" => "Content"]);

/* Урл */
$url = "";
if (P::get("_page", "url_hierarchy"))
{
	$path = _Page::path($_GET['id']);
	foreach ($path as $p)
	{
		$url .= "/" . $p['Url'];
	}
}
else
{
	if (P::get("_page", "url_auto"))
	{
		$url = "/" . P::get("_page", "url_auto_prefix") . $page['Url'];
	}
	else
	{
		$url = "/" . $page['Url'];
	}
}

$full_url = "http://" . DOMAIN . $url;
if (HTTPS_ENABLE)
{
	$full_url = "https://" . DOMAIN . $url;
}

/* Версионность и автосохранение */
version("page/{$page['ID']}");
autosave();
?>