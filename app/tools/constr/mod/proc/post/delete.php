<?php
$proc = _Proc::delete($_GET['id']);

mess_ok("proc «{$proc['Identified']} ({$proc['Name']})» удалён.");
redirect("#module/edit?id={$proc['Module_ID']}#tab_structure_proc");
?>