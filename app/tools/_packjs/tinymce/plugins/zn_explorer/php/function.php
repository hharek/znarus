<?php
/* Запрещённые расширения */
$ext_deny = [];
if (!empty(ZN_EXPLORER_FILE_EXTENSION_DENY))
{
	$ext_deny = explode(",", ZN_EXPLORER_FILE_EXTENSION_DENY);
	foreach ($ext_deny as $k => $v)
	{
		$ext_deny[$k] = trim($v);
	}
}

/**
 * Проверить является ли запрещенным файлом
 * 
 * @param string $file
 * @return boolean
 */
function is_deny($file)
{
	global $ext_deny;
	
	/* Определить расширение */
	$ext = "";
	$explode = explode(".", $file);
	if ($explode < 1)
	{
		return false;
	}
	$ext = end($explode);
	
	/* Проверка */
	if (in_array($ext, $ext_deny))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/* Расширения для рисунков */
if (empty(trim(ZN_EXPLORER_IMAGE_EXTENSION)))
{
	throw new Exception("Укажите расширения для рисунков.");
}
$ext_image = explode(",", ZN_EXPLORER_IMAGE_EXTENSION);
foreach ($ext_image as $k => $v)
{
	$ext_image[$k] = trim($v);
}

/**
 * Является ли файл рисуноком
 * 
 * @param string $file
 * @return boolean
 */
function is_image($file)
{
	global $ext_image;
	
	/* Определить расширение */
	$ext = "";
	$explode = explode(".", $file);
	if ($explode < 1)
	{
		return false;
	}
	$ext = end($explode);
	
	/* Проверка */
	if (in_array($ext, $ext_image))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * Содержит ли имя файла только ASCII-символы, а также «_», «.», «-»
 * 
 * @param string $file
 * @return boolean
 */
function is_ascii($file)
{
	return ctype_alnum(str_replace(["_", ".", "-"], "", $file));
}
?>