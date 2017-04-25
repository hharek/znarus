<?php
if (empty($_POST['Html_ID']))
{
	$_POST['Html_ID'] = null;
}

if (empty($_POST['Parent']))
{
	$_POST['Parent'] = null;
}

$page = _Page::add($_POST);

/* Версионность и черновик */
$vdata = 
[
	"Name" => $_POST['Name'],
	"Content" => $_POST['Content']
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
G::draft()->delete("page/add");

/* Страница добавлена */
mess_ok("Добавлена страница «{$page['Name']}».");
redirect("#_page/list");
?>