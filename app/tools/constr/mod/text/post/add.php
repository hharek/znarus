<?php
$text = _Text::add($_POST['Name'], $_POST['Identified'], $_POST['Value'], $_GET['module_id']);

mess_ok("text «{$text['Identified']} ({$text['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#tab_structure_text");
?>