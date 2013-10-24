<?php
$admin = ZN_Admin::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Get'], $_POST['Post'], $_POST['Visible']);

mess_ok("admin «{$admin['Identified']} ({$admin['Name']})» отредактирован.");
require "edit.php";
?>