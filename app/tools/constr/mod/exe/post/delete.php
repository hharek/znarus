<?php
$exe = _Exe::delete($_GET['id']);

mess_ok("exe «{$exe['Identified']} ({$exe['Name']})» удалён.");
redirect("#module/edit?id={$exe['Module_ID']}#tab_structure_exe");
?>