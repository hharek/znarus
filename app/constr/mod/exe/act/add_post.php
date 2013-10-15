<?php
$exe = ZN_Exe::add($_POST['Name'], $_POST['Identified'], $_GET['module_id']);

mess_ok("exe «{$exe['Identified']} ({$exe['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#structure_exe");
?>