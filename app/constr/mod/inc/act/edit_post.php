<?php
$inc = ZN_Inc::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Active']);

mess_ok("inc «{$inc['Identified']} ({$inc['Name']})» отредактирован.");
require "edit.php";
?>