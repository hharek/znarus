<?php
$url = _Seo_Url::add($_POST['Url'], $_POST['Title'], $_POST['Keywords'], $_POST['Description']);

mess_ok("Адрес для продвижения «{$url['Url']}» добавлен успешно.");
redirect("#_seo/url");
?>