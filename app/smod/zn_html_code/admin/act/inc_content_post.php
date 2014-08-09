<?php
$inc = ZN_Inc::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($inc['Module_ID']);

Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/inc/html/{$inc['Identified']}.html", $_POST['Content']);
mess_ok("Inc «{$module['Identified']}_{$inc['Identified']} ({$module['Name']} / {$inc['Name']}) отредактирован.»");
//reload();
?>