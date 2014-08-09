<?php
/* Реестр и конфигурация */
require __DIR__ . "/../../sys/reg.php";
require __DIR__ . "/../../conf/conf.php";
require __DIR__ . "/../../conf/options.php";

/* Недостающие данные из-за CLI */
error_reporting(0);
mb_internal_encoding("UTF-8");

/* Инициализация */
require __DIR__ . "/../../front/01_init.php";

/* Поиск файлов tags.php */
$tags_file = [];

$path_mod = scandir(Reg::path_app() . "/mod");
foreach ($path_mod as $val)
{
	if($val === ".." or $val === ".")
	{ continue; }
	
	if(is_file(Reg::path_app() . "/mod/" . $val . "/tags.php"))
	{ $tags_file[] = Reg::path_app() . "/mod/" . $val . "/tags.php"; }
}

$path_smod = scandir(Reg::path_app() . "/smod");
foreach ($path_smod as $val)
{
	if($val === ".." or $val === ".")
	{ continue; }
	
	if(is_file(Reg::path_app() . "/smod/" . $val . "/tags.php"))
	{ $tags_file[] = Reg::path_app() . "/smod/" . $val . "/tags.php"; }
}

/* Начало XML-а */
ZN_Tags::truncate();
echo 
<<<XML
<?xml version="1.0" encoding="utf-8"?>
<sphinx:docset xmlns:sphinx="http://sphinxsearch.com/">
	<sphinx:schema>
		<sphinx:field name="url" attr="string"/>
		<sphinx:field name="name" attr="string"/>
		<sphinx:field name="content" attr="string"/>
		<sphinx:field name="tags" attr="string"/>
	</sphinx:schema>
XML;

/* Сборка XML-а от других модулей */
$count = 1; 
foreach ($tags_file as $_file)
{
	/* Исполнение файла и получение данных */
	$data = call_user_func(function ($_file)
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
	
	/* Перевод данных в XML */
	if(!empty($data) and is_array($data))
	{
		foreach ($data as $val)
		{
			$val['tags'] = trim($val['tags']);
			if(!empty($val['tags']))
			{
				/* XML */
				echo 
<<<XML
<sphinx:document id="{$count}">
	<url><![CDATA[{$val['url']}]]></url>
	<name><![CDATA[{$val['name']}]]></name>
	<content><![CDATA[{$val['content']}]]></content>
	<tags><![CDATA[{$val['tags']}]]></tags>
</sphinx:document>
XML;
				$count++;
			}
		}
	}
}

/* Конец XML-а */
echo 
<<<XML
</sphinx:docset>
XML;

/* Дата последнего изменения */
P::set("zn_tags", "last_modified", date("Y-m-d H:i:s"));
?>
