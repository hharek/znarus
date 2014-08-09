<?php
$exe = ZN_Exe::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($exe['Module_ID']);

title("Правка exe «{$module['Identified']}_{$exe['Identified']} ({$module['Name']}_{$exe['Name']})»");

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

$path[] = "Правка exe «{$module['Identified']}_{$exe['Identified']}» [#zn_html_code/exe_content?id={$exe['ID']}]";
path($path);
?>