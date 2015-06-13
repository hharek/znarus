<?php
$proc = _Proc::add($_POST['Name'], $_POST['Identified'], $_GET['module_id']);

mess_ok("proc «{$proc['Identified']} ({$proc['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#tab_structure_proc");
?>