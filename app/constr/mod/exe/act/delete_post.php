<?php
$exe = ZN_Exe::delete($_GET['id']);

mess_ok("exe «{$exe['Identified']} ({$exe['Name']})» удалён.");
redirect("#module/edit?id={$exe['Module_ID']}#structure_exe");
?>