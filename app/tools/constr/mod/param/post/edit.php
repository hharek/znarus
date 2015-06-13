<?php
$param = _Param::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Type'], $_POST['Value']);

mess_ok("param «{$param['Identified']} ({$param['Name']})» отредактирован.");
?>