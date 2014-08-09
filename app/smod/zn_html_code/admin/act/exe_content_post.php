<?php
$exe = ZN_Exe::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($exe['Module_ID']);

Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/exe/html/{$exe['Identified']}.html", $_POST['Content']);
mess_ok("Exe «{$module['Identified']}_{$exe['Identified']} ({$module['Name']} / {$exe['Name']}) отредактирован.»");
//reload();
?>