<?php
$html = ZN_Html::select_line_by_id($_GET['id']);

title("Правка шаблона «{$html['Identified']}.html ({$html['Name']})»");

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

$path[] = "{$html['Identified']}.html [#zn_html_code/html_content?id={$html['ID']}]";
path($path);


?>