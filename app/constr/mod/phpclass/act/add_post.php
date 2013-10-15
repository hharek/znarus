<?php
$phpclass = ZN_Phpclass::add($_POST['Name'], $_POST['Identified'], $_GET['module_id']);

mess_ok("phpclass «{$phpclass['Identified']} ({$phpclass['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#structure_phpclass");
?>