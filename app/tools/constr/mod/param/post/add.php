<?php
$param = _Param::add($_POST['Name'], $_POST['Identified'], $_POST['Type'], $_POST['Value'], $_GET['module_id']);

mess_ok("param «{$param['Identified']} ({$param['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#tab_structure_param");
?>