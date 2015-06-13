<?php
$html = _Html::get($_GET['id']);

version("_html_code/html_{$html['ID']}");
title("Правка шаблона «{$html['Identified']}.html ({$html['Name']})»");

$path = [];
if($_GET['from'] === "url")
{
	$back_url = "#_html_code/url?url={$_GET['url']}";
	$path[] = "Управление [{$back_url}]"; 
}
elseif($_GET['from'] === "html")
{
	$back_url = "#_html_code/html";
	$path[] = "Шаблоны [{$back_url}]";
}
elseif($_GET['from'] === "module")
{
	$back_url = "#_html_code/module";
	$path[] = "Модули [{$back_url}]";
}

$path[] = "{$html['Identified']}.html [#_html_code/html_content?id={$html['ID']}]";
path($path);

packjs("codemirror", ["name"=>"Content"]);
?>