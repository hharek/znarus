<?php
$exe = ZN_Exe::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Active']);

mess_ok("exe «{$exe['Identified']} ({$exe['Name']})» отредактирован.");
require "edit.php";
?>