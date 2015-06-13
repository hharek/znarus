<?php
$admin = _Admin::delete($_GET['id']);

mess_ok("admin «{$admin['Identified']} ({$admin['Name']})» удалён.");
redirect("#module/edit?id={$admin['Module_ID']}#tab_structure_admin");
?>