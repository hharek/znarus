<?php
$admin = _Admin::edit
(
	$_GET['id'], 
	$_POST['Name'],
	$_POST['Identified'], 
	$_POST['Get'], 
	$_POST['Post'], 
	$_POST['Visible'], 
	$_POST['Window'],
	$_POST['Allow_All']
);

mess_ok("admin «{$admin['Identified']} ({$admin['Name']})» отредактирован.");
?>