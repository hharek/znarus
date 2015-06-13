<?php
$redirect =  _Seo_Redirect::edit($_GET['id'], $_POST['From'], $_POST['To'], $_POST['Location']);

mess_ok("Переадресация c «{$redirect['From']}» отредактирована.");
?>