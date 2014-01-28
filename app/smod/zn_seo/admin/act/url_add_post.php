<?php
$url = ZN_Seo_Url::add($_POST['Url'], $_POST['Title'], $_POST['Keywords'], $_POST['Description']);

mess_ok("Адрес для продвижения «{$url['Url']}» добавлен успешно.");
redirect("#zn_seo/url");
?>