<?php
$text = ZN_Text::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Value']);

mess_ok("text «{$text['Identified']} ({$text['Name']})» отредактирован.");
require "edit.php";
?>