<?php
$inc = ZN_Inc::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($inc['Module_ID']);

title("Правка inc «{$module['Identified']}_{$inc['Identified']} ({$module['Name']}_{$inc['Name']})»");

$path = [];

if($_GET['from'] === "url")
{
	$back_url = "#zn_html_code/url?url={$_GET['url']}";
	$path[] = "Управление [{$back_url}]"; 
}
elseif($_GET['from'] === "html")
{
	$back_url = "#zn_html_code/html";
	$path[] = "Шаблоны [{$back_url}]";
}
elseif($_GET['from'] === "module")
{
	$back_url = "#zn_html_code/module";
	$path[] = "Модули [{$back_url}]";
}

$path[] = "Правка inc «{$module['Identified']}_{$inc['Identified']}» [#zn_html_code/inc_content?id={$inc['ID']}]";
path($path);
?>