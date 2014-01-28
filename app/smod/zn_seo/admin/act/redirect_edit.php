<?php
$redirect = ZN_Seo_Redirect::select_line_by_id($_GET['id']);

title("Редактировать переадресацию с «{$redirect['From']}»");
path
([
	"Переадресация [#zn_seo/redirect]",
	"«{$redirect['From']}» [#zn_seo/redirect_edit?id={$_GET['id']}]"
]);
?>