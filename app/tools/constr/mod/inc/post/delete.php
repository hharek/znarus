<?php
$inc = _Inc::delete($_GET['id']);

mess_ok("inc «{$inc['Identified']} ({$inc['Name']})» удалён.");
redirect("#module/edit?id={$inc['Module_ID']}#tab_structure_inc");
?>