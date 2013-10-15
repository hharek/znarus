<?php
$text = ZN_Text::delete($_GET['id']);

mess_ok("text «{$text['Identified']} ({$text['Name']})» удалён.");
redirect("#module/edit?id={$text['Module_ID']}#structure_text");
?>