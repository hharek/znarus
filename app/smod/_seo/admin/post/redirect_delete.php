<?php
$redirect = _Seo_Redirect::delete($_GET['id']);

mess_ok("Переадресация с «{$redirect['From']}» удалёна.");
redirect("#_seo/redirect");
?>