<?php
$text = _Text::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Value']);

mess_ok("text «{$text['Identified']} ({$text['Name']})» отредактирован.");
?>