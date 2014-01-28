<?php
$current_url = "";
if(!empty($_GET['url']))
{$current_url = $_GET['url'];}

$url = ZN_Seo_Url::select_list_by_url($current_url);

title("Адреса для продвижения");
path
([
	"Адреса для продвижения [#zn_seo/url]"
]);
?>