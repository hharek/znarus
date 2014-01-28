<?php
$url = ZN_Seo_Url::select_line_by_id($_GET['id']);

title("Редактировать адрес для продвижения «{$url['Url']}»");
path
([
	"Адреса для продвижения [#zn_seo/url]",
	"«{$url['Url']}» [#zn_seo/url_edit?id={$_GET['id']}]"
]);
?>