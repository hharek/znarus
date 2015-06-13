<?php
$admin = _Admin::add($_POST['Name'], $_POST['Identified'], $_POST['Get'], $_POST['Post'], $_POST['Visible'], $_GET['module_id']);

mess_ok("admin «{$admin['Identified']} ({$admin['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#tab_structure_admin");
?>