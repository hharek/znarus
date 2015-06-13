<?php
$exe = _Exe::edit
(
	$_GET['id'], 
	$_POST['Name'], 
	$_POST['Identified'], 
	$_POST['Cache_Route'],
	$_POST['Cache_Page'],
	$_POST['Active']
);

mess_ok("exe «{$exe['Identified']} ({$exe['Name']})» отредактирован.");
?>