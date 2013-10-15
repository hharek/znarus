<?php
$phpclass = ZN_Phpclass::edit($_GET['id'], $_POST['Name'], $_POST['Identified']);

mess_ok("phpclass «{$phpclass['Identified']} ({$phpclass['Name']})» отредактирован.");
require "edit.php";
?>