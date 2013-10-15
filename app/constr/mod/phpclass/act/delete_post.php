<?php
$phpclass = ZN_Phpclass::delete($_GET['id']);

mess_ok("phpclass «{$phpclass['Identified']} ({$phpclass['Name']})» удалён.");
redirect("#module/edit?id={$phpclass['Module_ID']}#structure_phpclass");
?>