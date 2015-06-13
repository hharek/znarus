<?php
$packjs = _Packjs::delete($_GET['id']);

mess_ok("Пакет JavaScript «{$packjs['Identified']}» ({$packjs['Name']}) удалён.");
redirect("#packjs/list");
?>