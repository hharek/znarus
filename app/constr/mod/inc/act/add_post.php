<?php
$inc = ZN_Inc::add($_POST['Name'], $_POST['Identified'], $_GET['module_id']);

mess_ok("inc «{$inc['Identified']} ({$inc['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#structure_inc");
?>