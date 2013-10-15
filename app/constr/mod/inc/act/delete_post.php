<?php
$inc = ZN_Inc::delete($_GET['id']);

mess_ok("inc «{$inc['Identified']} ({$inc['Name']})» удалён.");
redirect("#module/edit?id={$inc['Module_ID']}#structure_inc");
?>