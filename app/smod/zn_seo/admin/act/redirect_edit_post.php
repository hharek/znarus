<?php
$redirect =  ZN_Seo_Redirect::edit($_GET['id'], $_POST['From'], $_POST['To']);

mess_ok("Переадресация c «{$redirect['From']}» отредактирована.");
reload();
?>