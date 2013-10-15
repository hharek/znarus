<?php
$admin = ZN_Admin::delete($_GET['id']);

mess_ok("admin «{$admin['Identified']} ({$admin['Name']})» удалён.");
redirect("#module/edit?id={$admin['Module_ID']}#structure_admin");
?>