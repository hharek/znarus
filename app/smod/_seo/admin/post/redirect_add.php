<?php
$redirect = _Seo_Redirect::add($_POST['From'], $_POST['To'], $_POST['Location'], $_POST['Tags']);

mess_ok("Переадесация с «{$redirect['From']}» добавлена успешно.");
redirect("#_seo/redirect");
?>