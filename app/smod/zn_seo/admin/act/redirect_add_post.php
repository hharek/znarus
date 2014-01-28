<?php
$redirect = ZN_Seo_Redirect::add($_POST['From'], $_POST['To']);

mess_ok("Переадесация с «{$redirect['From']}» добавлена успешно.");
redirect("#zn_seo/redirect");
?>