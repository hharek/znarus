<?php
$param = _Param::delete($_GET['id']);

mess_ok("param «{$param['Identified']} ({$param['Name']})» удалён.");
redirect("#module/edit?id={$param['Module_ID']}#tab_structure_param");
?>