<?php
$current_from = "";
if (!empty($_GET['from']))
{
	$current_from = $_GET['from'];
}

$redirect = _Seo_Redirect::get_by_from($current_from);

title("Переадресация");
path(["Переадресация"]);
?>