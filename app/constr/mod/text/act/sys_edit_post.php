<?php
$text = ZN_Text::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Value']);

mess_ok("Системный текст «{$text['Identified']} ({$text['Name']})» отредактирован.");
require "sys_edit.php";
?>