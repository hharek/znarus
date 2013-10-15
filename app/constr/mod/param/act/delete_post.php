<?php
$param = ZN_Param::delete($_GET['id']);

mess_ok("param «{$param['Identified']} ({$param['Name']})» удалён.");
redirect("#module/edit?id={$param['Module_ID']}#structure_param");
?>