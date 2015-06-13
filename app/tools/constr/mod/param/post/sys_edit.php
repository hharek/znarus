<?php
$param = _Param::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Type'], $_POST['Value']);

mess_ok("Системный параметр «{$param['Identified']} ({$param['Name']})» отредактирован.");
?>