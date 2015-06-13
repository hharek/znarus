<?php
$text = _Text::delete($_GET['id']);

mess_ok("text «{$text['Identified']} ({$text['Name']})» удалён.");
redirect("#module/edit?id={$text['Module_ID']}#tab_structure_text");
?>