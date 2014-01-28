<?php
$redirect = ZN_Seo_Redirect::delete($_GET['id']);

mess_ok("Переадресация с «{$redirect['From']}» удалёна.");
redirect("#zn_seo/redirect");
?>