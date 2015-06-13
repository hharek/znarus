<?php
$ajax = _Ajax::delete($_GET['id']);

mess_ok("ajax «{$ajax['Identified']} ({$ajax['Name']})» удалён.");
redirect("#module/edit?id={$ajax['Module_ID']}#tab_structure_ajax");
?>