<?php
$proc = ZN_Proc::add($_POST['Name'], $_POST['Identified'], $_GET['type'], $_GET['module_id']);

mess_ok("proc_{$_GET['type']} «{$proc['Identified']} ({$proc['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#structure_proc_{$_GET['type']}");
?>