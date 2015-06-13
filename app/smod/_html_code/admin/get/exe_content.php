<?php
$exe = _Exe::get($_GET['id']);
$module = _Module::get($exe['Module_ID']);

version("_html_code/exe_{$exe['ID']}");

title("Правка exe «{$module['Identified']}_{$exe['Identified']} ({$module['Name']}_{$exe['Name']})»");

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

$path[] = "Правка exe «{$module['Identified']}_{$exe['Identified']}» [#_html_code/exe_content?id={$exe['ID']}]";
path($path);

packjs("codemirror", ["name" => "Content"]);
?>