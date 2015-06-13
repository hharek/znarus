<?php
$redirect = _Seo_Redirect::add($_POST['From'], $_POST['To'], $_POST['Location']);

mess_ok("Переадесация с «{$redirect['From']}» добавлена успешно.");
redirect("#_seo/redirect");
?>