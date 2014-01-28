<?php
Reg::file()->chroot_enable();

/* Путь */
$path = ".";
if(!empty($_GET['path']))
{$path = $_GET['path'];}

$dir = Reg::file()->ls($path, "dir");
$file = Reg::file()->ls($path, "file");

foreach ($file as $key=>$val)
{
	/* Не показываем .htaccess */
	if($val['name'] === ".htaccess")
	{unset($file[$key]);}
	
	/* Не показываем php */
	if(mb_substr($val['name'], -4) === ".php")
	{unset($file[$key]);}
}

path
([
	"Проводник [#zn_explorer/ls?path=.]"
]);

title("Проводник «{$path}»");
?>