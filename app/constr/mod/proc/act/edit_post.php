<?php
$proc = ZN_Proc::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Active']);

mess_ok("proc_{$proc['Type']} «{$proc['Identified']} ({$proc['Name']})» отредактирован.");
require "edit.php";
?>