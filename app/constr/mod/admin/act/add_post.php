<?php
$admin = ZN_Admin::add($_POST['Name'], $_POST['Identified'], $_GET['module_id']);

mess_ok("admin «{$admin['Identified']} ({$admin['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#structure_admin");
?>