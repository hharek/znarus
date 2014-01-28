<?php
$param = ZN_Param::delete($_GET['id']);

mess_ok("Системный параметр «{$param['Identified']} ({$param['Name']}) удалён.»");
redirect("#param/sys");
?>