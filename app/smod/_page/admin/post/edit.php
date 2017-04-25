<?php
if (empty($_POST['Html_ID']))
{
	$_POST['Html_ID'] = null;
}

if (empty($_POST['Parent']))
{
	$_POST['Parent'] = null;
}

$page = _Page::edit($_POST, $_GET['id']);

if (!isset($_GET['_autosave']))
{
	$vdata = 
	[
		"Name" => $_POST['Name'],
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	];
	if (!empty($_POST['Html_ID']))
	{
		$vdata['Html_ID'] = $_POST['Html_ID'];
	}
	if (!empty($_POST['Parent']))
	{
		$vdata['Parent'] = $_POST['Parent'];
	}
	if (!empty($_POST['Url']))
	{
		$vdata['Url'] = $_POST['Url'];
	}
	
	G::version()->set("page/" . $page['ID'], $vdata);
}
mess_ok("Страница «{$page['Name']}» сохранена.");
?>