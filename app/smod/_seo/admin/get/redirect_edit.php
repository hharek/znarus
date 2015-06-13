<?php
$redirect = _Seo_Redirect::get($_GET['id']);

title("Редактировать переадресацию с «{$redirect['From']}»");
path
([
	"Переадресация [#_seo/redirect]",
	"«{$redirect['From']}»"
]);
?>