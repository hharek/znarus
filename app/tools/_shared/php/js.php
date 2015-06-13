<?php
/* Папки */
$_js_index = DIR_VAR . "/tools/" . G::url_path_ar()[0];
$_js_dir = DIR_TOOLS . "/_shared/js";

/* Изменялись ли файлы js */
$_js_index_modify_time = 0;
if (is_file($_js_index))
{
	$_js_index_modify_time = filemtime($_js_index);
}

if (G::url_path_ar()[0] === "index.js")
{
	$_js = 
	[
		"translit.js",
		"jquery-2.1.3.min.js",
		"jquery.cookie.js",
		"default.js",
		"menu.js",
		"okno.js",
		"hash.js",
		"exe.js",
		"error.js",
		"success.js",
		"path.js",
		"tab.js",
		"sort.js",
		"version.js",
		"draft.js",
		"autosave.js",
		"packjs.js"
	];
}
elseif (G::url_path_ar()[0] === "auth.js")
{
	$_js = 
	[
		"jquery-2.1.3.min.js",
		"auth.js",
		"okno.js",
		"jsencrypt.js"
	];
}
elseif (G::url_path_ar()[0] === "restore.js")
{
	$_js = 
	[
		"jquery-2.1.3.min.js",
		"restore.js",
		"okno.js"
	];
}

$_js_modify = false;
foreach ($_js as $_val)
{
	if(filemtime($_js_dir . "/" . $_val) > $_js_index_modify_time)
	{
		$_js_modify = true;
		break;
	}
}

/* Создать общий JS */
if($_js_modify === true)
{
	$_js_index_content = "";
	foreach ($_js as $_val)
	{
		$_js_index_content .= file_get_contents($_js_dir . "/" . $_val) . "\n\n";
	}
	file_put_contents($_js_index, $_js_index_content);
}

/* Вывод */
header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($_js_index)) . " GMT");
header("Content-Type: application/x-javascript; charset=utf-8");
readfile($_js_index);
exit();
?>