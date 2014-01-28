<?php
$param = ZN_Param::add($_POST['Name'], $_POST['Identified'], $_POST['Type'], $_POST['Value'], 0);

mess_ok("Системный параметр «{$param['Identified']} ({$param['Name']})» добавлен.");
redirect("#param/sys");
?>