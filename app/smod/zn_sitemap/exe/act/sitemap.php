<?php
Reg::title("Карта-сайта");

/* Поиск файлов sitemap */
$sitemap_file = [];

$path_mod = scandir(Reg::path_app() . "/mod");
foreach ($path_mod as $val)
{
	if($val === ".." or $val === ".")
	{ continue; }
	
	if(is_file(Reg::path_app() . "/mod/" . $val . "/sitemap.php"))
	{ $sitemap_file[] = Reg::path_app() . "/mod/" . $val . "/sitemap.php"; }
}

$path_smod = scandir(Reg::path_app() . "/smod");
foreach ($path_smod as $val)
{
	if($val === ".." or $val === ".")
	{ continue; }
	
	if(is_file(Reg::path_app() . "/smod/" . $val . "/sitemap.php"))
	{ $sitemap_file[] = Reg::path_app() . "/smod/" . $val . "/sitemap.php"; }
}

/* Данные по карте сайта */
$data = [];
foreach ($sitemap_file as $_file)
{
	/* Исполнение файла и получение данных */
	$_data = call_user_func(function ($_file)
	{
		/* Подгрузка PHP классов */
		if(is_dir(dirname($_file) . "/class"))
		{
			$_class_ar = scandir(dirname($_file) . "/class");
			foreach ($_class_ar as $_class)
			{
				if (is_file(dirname($_file) . "/class/" . $_class) and mb_substr($_class, -4) === ".php")
				{ require_once dirname($_file) . "/class/" . $_class; }
			}
		}
		
		return require $_file;
		
	}, $_file);
	
	/* Объединяем массив */
	if(!empty($_data) and is_array($_data))
	{
		$data = array_merge($data, $_data);
	}
}
?>