<?php
/* Проверка */
$identified = $_GET['identified'];
if (!Type::check("identified", $identified))
{
	throw new Exception("Идентификатор куска html-а задан неверно.");
}

/* Мета */
title("Правка куска html-а «{$_GET['identified']}»");
$back_url = "#_html_code/url?url={$_GET['url']}";
$path[] = "Управление [{$back_url}]";
$path[] = "Правка куска html-а «{$_GET['identified']}»";
path($path);

/* Codemirror */
packjs("codemirror", ["name" => "Content"]);
?>