<?php
/* Создать папку для инструметов */
if (!is_dir(DIR_VAR . "/tools/"))
{
	mkdir(DIR_VAR . "/tools/");
}

/* Папки */
$_css_index = DIR_VAR . "/tools/" . G::url_path_ar()[0];
$_css_dir = DIR_TOOLS . "/_shared/css";

/* Изменялись ли файлы css */
$_css_index_modify_time = 0;
if (is_file($_css_index))
{
	$_css_index_modify_time = filemtime($_css_index);
}

$_css = 
[
	"basic.css",
	"icon.css",
	"std.css",
	"okno.css",
	"index.css",
	"menu.css"
];

if (G::url_path_ar()[0] === "auth.css")
{
	$_css[] = "auth.css";
}
elseif (G::url_path_ar()[0] === "restore.css")
{
	$_css[] = "restore.css";
}
elseif (G::url_path_ar()[0] === "index.css")
{
	$_css[] = "index.css";
	$_css[] = "menu.css";
}

$_css_modify = false;
foreach ($_css as $_val)
{
	if (filemtime($_css_dir . "/" . $_val) > $_css_index_modify_time)
	{
		$_css_modify = true;
		break;
	}
}

/* Сформировать css */
if ($_css_modify === true)
{
	/* Создать общий css */
	$_css_content = "";
	foreach ($_css as $_val)
	{
		$_css_content .= file_get_contents($_css_dir . "/" . $_val) . "\n\n";
	}
	
	/* Сформировать */
	require DIR_APP . "/lib/lessphp/lessc.inc.php";
	$_less = new lessc();
	file_put_contents($_css_index, $_less->compile($_css_content));
}

/* Вывод */
header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime(DIR_VAR . "/tools/" . G::url_path_ar()[0])) . " GMT");
header("Content-Type: text/css; charset=utf-8");
readfile(DIR_VAR . "/tools/" . G::url_path_ar()[0]);
exit();
?>