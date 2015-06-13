<?php
/**
 * Карта сайта
 */
class _Sitemap
{
	/**
	 * Домен
	 * 
	 * @var string
	 */
	private static $_domain = DOMAIN;
	
	/**
	 * Протокол
	 * 
	 * @var string
	 */
	private static $_protocol = "http";

	/**
	 * Путь к файл sitemap.xml
	 * 
	 * @var string
	 */
	private static $_sitemap_xml_path = DIR_WWW . "/sitemap.xml";
	
	/**
	 * Путь к файлу sitemap.xsd
	 * 
	 * @var strig
	 */
	private static $_sitemap_xsd = DIR_APP . "/smod/_sitemap/other/sitemap.xsd";
	
	/**
	 * Содержимое sitemap.xml
	 * 
	 * @var string
	 */
	private static $_sitemap_xml;
	
	/**
	 * Данные для страницы «Карта сайта»
	 * 
	 * @var array 
	 */
	private static $_data;

	/**
	 * Сформировать sitemap.xml
	 */
	public static function xml()
	{
		/* Определить протокол */
		if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === "on")
		{
			self::$_protocol = "https";
		}
		
		/* Модули имеющие функцию Page Info */
		$module = G::db_core()->module_page_info()->assoc();
		
		/* Запускаем функцию Page Info для каждого модуля */
		foreach ($module as $val)
		{
			$func = self::_func_or_method($val['Page_Info_Function']);
			self::$_sitemap_xml .= self::_xml($func);
		}
		
		/* Всавляем */
		$protocol = &self::$_protocol;
		$domain = &self::$_domain;
		$sitemap_xml = &self::$_sitemap_xml;
		self::$_sitemap_xml = 
<<<XML
<?xml version="1.0" encoding="UTF-8"?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>{$protocol}://{$domain}/</loc>
	</url>
	{$sitemap_xml}
</urlset>
XML;
		
		/* DOM XML */
		$dom_xml = new DOMDocument("1.0", "UTF-8");
		$dom_xml->preserveWhiteSpace = false;
		$dom_xml->formatOutput = true;
		$dom_xml->loadXML(self::$_sitemap_xml);
		$dom_xml->schemaValidate(self::$_sitemap_xsd);
		
		/* Сохранить в файл sitemap.xml */
		G::file()->put(self::$_sitemap_xml_path, $dom_xml->saveXML());
	}
	
	/**
	 * Данные для страницы «Карта сайта»
	 * 
	 * @return array
	 */
	public static function data()
	{
		/* Модули имеющие функцию Page Info */
		$module = G::db_core()->module_page_info()->assoc();
		
		/* Запускаем функцию Page Info для каждого модуля */
		$data = [];
		foreach ($module as $val)
		{
			$func = self::_func_or_method($val['Page_Info_Function']);
			$data[] = self::_data($func);
		}
		
		return $data;
	}
	
	/**
	 * Вывод xml-а по странице и подчинённых страниц (рекурсия)
	 * 
	 * @param mixed $function
	 * @param mixed $param
	 * @return string
	 */
	private static function _xml($function, $param = null)
	{
		/* Сведения по странице */
		$page = call_user_func($function, $param);
		
		/* XML по странице */
		$xml = "";
		if (empty($page['disable']))
		{
			$xml .= "<url>";
			
			$xml .= "<loc>" . self::$_protocol . "://" . self::$_domain . $page['url'] . "</loc>";			/* Url */
			
			if (!empty($page['last_modified']))
			{
				$xml .= "<lastmod>" . date("Y-m-d", strtotime($page['last_modified'])) . "</lastmod>";		/* Last-Modified */
			}
			
			$xml .= "</url>";
		}

		/* Подчинённые страницы */
		if (!empty($page['child']))
		{
			foreach ($page['child'] as $val)
			{
				$xml .= self::_xml($function, $val['param']);
			}
		}
		
		return $xml;
	}
	
	/**
	 * Получить данные по странице и подчинённых страниц (рекурсия)
	 * 
	 * @param mixed $function
	 * @param mixed $param
	 * @return array
	 */
	private static function _data($function, $param = null)
	{
		/* Сведения по странице */
		$data = call_user_func($function, $param);
		unset($data['content']);
		
		/* Подчинённые страницы */
		if (!empty($data['child']))
		{
			$child = [];
			foreach ($data['child'] as $val)
			{
				$child[] = self::_data($function, $val['param']);
			}
			
			$data['child'] = $child;
		}
		
		return $data;
	}

	/**
	 * Если строка является методом вернуть массив, иначе строку
	 * 
	 * @param string $string
	 * @return mixed
	 */
	private static function _func_or_method($string)
	{
		if (strpos($string, "::") === false)
		{
			return $string;
		}
		else
		{
			return explode("::", $string);
		}
	}
}
?>