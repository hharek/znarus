<?php
$url =  _Seo_Url::edit($_GET['id'], $_POST['Url'], $_POST['Title'], $_POST['Keywords'], $_POST['Description']);

mess_ok("Адрес для продвижения «{$url['Url']}» отредактирован.");
?>