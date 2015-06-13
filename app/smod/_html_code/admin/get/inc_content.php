<?php
$inc = _Inc::get($_GET['id']);
$module = _Module::get($inc['Module_ID']);

version("_html_code/inc_{$inc['ID']}");

title("Правка inc «{$module['Identified']}_{$inc['Identified']} ({$module['Name']}_{$inc['Name']})»");

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

$path[] = "Правка inc «{$module['Identified']}_{$inc['Identified']}» [#_html_code/inc_content?id={$inc['ID']}]";
path($path);

packjs("codemirror", ["name" => "Content"]);
?>