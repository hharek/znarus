<?php
$url = _Seo_Url::delete($_GET['id']);

mess_ok("Адрес для продвижения «{$url['Url']}» удалён.");
redirect("#_seo/url");
?>