<?php
$proc = ZN_Proc::delete($_GET['id']);

mess_ok("proc_{$proc['Type']} «{$proc['Identified']} ({$proc['Name']})» удалён.");
redirect("#module/edit?id={$proc['Module_ID']}#structure_proc_{$proc['Type']}");
?>