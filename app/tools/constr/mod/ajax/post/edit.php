<?php
$ajax = _Ajax::edit
(
	$_GET['id'], 
	$_POST['Identified'], 
	$_POST['Name'], 
	$_POST['Get'],
	$_POST['Post'],
	$_POST['Cache'],
	$_POST['Active']
);

mess_ok("ajax «{$ajax['Identified']} ({$ajax['Name']})» отредактирован.");
?>