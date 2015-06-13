<?php
$current_url = "";
if (!empty($_GET['url']))
{
	$current_url = $_GET['url'];
}

$url = _Seo_Url::find_by_url($current_url);

title("Адреса для продвижения");
path (["Адреса для продвижения"]);
?>