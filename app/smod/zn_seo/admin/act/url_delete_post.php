<?php
$url = ZN_Seo_Url::delete($_GET['id']);

mess_ok("Адрес для продвижения «{$url['Url']}» удалён.");
redirect("#zn_seo/url");
?>