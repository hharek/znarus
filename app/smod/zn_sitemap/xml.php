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

/* Поиск файлов sitemap */
$sitemap_file = [];

$path_mod = scandir(Reg::path_app() . "/mod");
foreach ($path_mod as $val)
{
	if($val === ".." or $val === ".")
	{ continue; }
	
	if(is_file(Reg::path_app() . "/mod/" . $val . "/sitemap_xml.php"))
	{ $sitemap_file[] = Reg::path_app() . "/mod/" . $val . "/sitemap_xml.php"; }
}

$path_smod = scandir(Reg::path_app() . "/smod");
foreach ($path_smod as $val)
{
	if($val === ".." or $val === ".")
	{ continue; }
	
	if(is_file(Reg::path_app() . "/smod/" . $val . "/sitemap_xml.php"))
	{ $sitemap_file[] = Reg::path_app() . "/smod/" . $val . "/sitemap_xml.php"; }
}

/* XML */
$domain = Reg::domain();
$xml = 
<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>http://{$domain}/</loc>
	</url>
XML;
	
foreach ($sitemap_file as $_file)
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
			$val['url'] = htmlspecialchars($val['url'], ENT_QUOTES, "UTF-8");
			
			$xml .= "<url>
						<loc>http://{$domain}{$val['url']}</loc>";
			
			if(!empty($val['date']))
			{
				$val['date'] = date("Y-m-d", strtotime($val['date']));
				$xml .= "<lastmod>{$val['date']}</lastmod>";
			}
				
			$xml .= "</url>";
		}
	}
}
	
$xml .=
<<<XML
</urlset>
XML;

/* DOM XML */
$dom_xml = new DOMDocument("1.0", "UTF-8");
$dom_xml->preserveWhiteSpace = false;
$dom_xml->formatOutput = true;
$dom_xml->loadXML($xml);
$dom_xml->schemaValidate(__DIR__ . "/sitemap.xsd");

Reg::file()->put("sitemap.xml", $dom_xml->saveXML());
?>