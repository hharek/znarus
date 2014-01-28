<?php
$current_from = "";
if(!empty($_GET['from']))
{$current_from = $_GET['from'];}

$redirect = ZN_Seo_Redirect::select_list_by_from($current_from);

title("Переадресация");
path
([
	"Переадресация [#zn_seo/redirect]"
]);
?>