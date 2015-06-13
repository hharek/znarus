<?php
/**
 * Поиск
 */
class _Search
{
	/**
	 * Запрос
	 * 
	 * @var string
	 */
	public static $query = "";

	/**
	 * Тип поискового движка
	 * 
	 * @var string 
	 */
	private static $_type;
	
	/**
	 * Кол-во результатов на страницу
	 * 
	 * @var int 
	 */
	private static $_limit;

	/**
	 * Порядковый номер документа в sphinx xml
	 * 
	 * @var int
	 */
	private static $_sphinx_xml_id = 1;

	/**
	 * Создать индекс по сайту
	 */
	public static function create_index()
	{
		/* Удалить старые данные */
		$query =
<<<SQL
TRUNCATE "search_index_tags" CASCADE;
TRUNCATE "search_index" CASCADE;
TRUNCATE "search_tags" CASCADE;
ALTER SEQUENCE "search_index_seq" RESTART;
ALTER SEQUENCE "search_tags_seq" RESTART;
SQL;
		G::db_core()->multi_query($query);
		
		/* Тип поискового движка */
		self::$_type = P::get("_search", "type");
		
		/* Модули имеющие функцию Page Info */
		$module = G::db_core()->module_page_info()->assoc();
		
		/* Если тип sphinx добавляем шапку XML */
		if (self::$_type === "sphinx")
		{
			echo 
<<<XML
<?xml version="1.0" encoding="utf-8"?>
<sphinx:docset xmlns:sphinx="http://sphinxsearch.com/">
	<sphinx:schema>
		<sphinx:field name="url" attr="string"/>
		<sphinx:field name="title" attr="string"/>
		<sphinx:field name="content" attr="string"/>
		<sphinx:field name="tags" attr="string"/>
		<sphinx:attr name="tags_id" type="multi" />
	</sphinx:schema>
XML;
		}
		
		/* Запускаем функцию Page Info для каждого модуля */
		foreach ($module as $val)
		{
			$func = self::_func_or_method($val['Page_Info_Function']);
			self::_add($func);
		}
		
		/* Если тип sphinx добавляем шапку XML */
		if (self::$_type === "sphinx")
		{
			echo 
<<<XML
</sphinx:docset>
XML;
		}
	}
	
	/**
	 * Найти
	 * 
	 * @param string $query
	 * @param int $page
	 * @return array
	 */
	public static function find($query, $page = 1)
	{
		/* Проверка */
		$query = self::_delete_bad_symbol($query);
		if (empty($query))
		{
			throw new Exception("Укажите слово для поиска.");
		}
		
		if (mb_strlen($query) < 3)
		{
			throw new Exception("Слово для поиска должно быть не меньше трёх символов.");
		}
		
		/* Определить тэги */
		$tags_ar = []; $match = [];
		preg_match_all("#\[.*?\]#isu", $query, $match);
		if (!empty($match[0]))
		{
			$query = str_replace($match[0], "", $query);
			$query = self::_delete_bad_symbol($query, false);
			
			foreach ($match[0] as $val)
			{
				$tags_ar[] = self::_delete_bad_symbol($val, false);
			}
		}
		
		/* Определить ID тэгов */
		$tags_id_ar = [];
		foreach ($tags_ar as $val)
		{
			$tags_one = G::db_core()->search_tags_get_by_name($val)->row();
			if (!empty($tags_one))
			{
				$tags_id_ar[] = $tags_one['ID'];
			}
		}
		
		/* Определить слова */
		$word_ar = explode(" ", $query);
		
		/* Текущий запрос */
		self::$query = "";
		if (!empty($word_ar))
		{
			self::$query .= implode(" ", $word_ar) . " ";
		}
		if (!empty($tags_ar))
		{
			self::$query .= "[". implode("][", $tags_ar) . "]";
		}
		
		/* Тип поискового движка */
		self::$_type = P::get("_search", "type");
		
		/* Кол-во результатов на страницу */
		self::$_limit = P::get("_search", "limit");
		
		/* Поиск через PostgreSQL */
		if (self::$_type === "pgsql")
		{
			$result = self::_find_pgsql($word_ar, $tags_id_ar, $page);
		}
		/* Поиск через sphinx */
		elseif (self::$_type === "sphinx")
		{
			$result = self::_find_sphinx($word_ar, $tags_id_ar, $page);
		}
		
		return $result;
	}
	
	/**
	 * Страница «Поиск»
	 * 
	 * @param type $param
	 */
	public static function page_info($param)
	{
		if (empty($param))
		{
			return
			[
				"url" => "/поиск",
				"title" => "Поиск по сайту",
				"content" => "Здесь вы можете воспользоваться поиском чтобы найти необходимую информацию.",
				"tags" => "поиск по сайту, найти, поиск"
			];
		}
	}

	/**
	 * Добавить страницу в индкес
	 * 
	 * @param mixed $function
	 * @param mixed $param
	 */
	private static function _add($function, $param = null)
	{
		/* Сведения по странице */
		$page = call_user_func($function, $param);
		
		/* Добавить */
		if (empty($page['disable']))
		{
			/* Не заданные поля */
			if (!isset($page['content']))
			{
				$page['content'] = "";
			}
			if (!isset($page['tags']))
			{
				$page['tags'] = "";
			}
			
			/* Добавить */
			if (self::$_type === "pgsql")
			{
				self::_add_pgsql($page['url'], $page['title'], $page['content'], $page['tags']);
			}
			elseif (self::$_type === "sphinx")
			{
				self::_add_sphinx($page['url'], $page['title'], $page['content'], $page['tags']);
			}
		}
		
		/* Подчинённые страницы */
		if (!empty($page['child']))
		{
			foreach ($page['child'] as $val)
			{
				self::_add($function, $val['param']);
			}
		}
		
	}

	/**
	 * Добавить страницу в индекс (pgsql)
	 * 
	 * @param string $url
	 * @param string $title
	 * @param string $content
	 * @param string $tags
	 */
	private static function _add_pgsql($url, $title, $content, $tags)
	{
		/* Добавить */
		$data = 
		[
			"Url" => $url,
			"Title" => $title,
			"Content" => $content,
			"Tags" => $tags
		];
		$index_id = G::db_core()->insert("search_index", $data, "ID");
		
		/* Тэги */
		if (!empty($tags))
		{
			$tags_ar = explode(",", $tags);
			foreach ($tags_ar as $tags_name)
			{
				$tags_name = trim($tags_name);
				$tags_id = (int)G::db_core()->search_tags_add($tags_name)->single();
				
				$data = 
				[
					"Index_ID" => $index_id,
					"Tags_ID" => $tags_id
				];
				G::db_core()->insert("search_index_tags", $data);
			}			
		}
	}
	
	/**
	 * Добавить страницу в индекс (echo XML sphinx)
	 * 
	 * @param string $url
	 * @param string $title
	 * @param string $content
	 * @param string $tags
	 */
	private static function _add_sphinx($url, $title, $content, $tags)
	{
		/* Подготовка */
		$id = self::$_sphinx_xml_id;
		$content = htmlspecialchars($content);
		
		/* Тэги */
		$tags_id_ar = [];
		if (!empty($tags))
		{
			$tags_ar = explode(",", $tags);
			foreach ($tags_ar as $tags_name)
			{
				$tags_name = trim($tags_name);
				$tags_id_ar[] = (int)G::db_core()->search_tags_add($tags_name)->single();
			}			
		}
		$tags_id = implode(",", $tags_id_ar);
		
		/* XML */
		echo 
<<<XML
<sphinx:document id="{$id}">
	<url><![CDATA[{$url}]]></url>
	<title><![CDATA[{$title}]]></title>
	<content><![CDATA[{$content}]]></content>
	<tags><![CDATA[{$tags}]]></tags>
	<tags_id>{$tags_id}</tags_id>
</sphinx:document>
XML;
		
		self::$_sphinx_xml_id ++;
	}
	
	/**
	 * Поиск через PostgreSQL
	 * 
	 * @param array $word_ar
	 * @param array $tags_id_ar
	 * @param int $page
	 * @return array
	 */
	private static function _find_pgsql($word_ar, $tags_id_ar, $page)
	{
		/* Подготовка запроса */
		$word = "";
		if (!empty($word_ar))
		{
			$word = implode(" & ", $word_ar);
		}
		
		$tags_id = "";
		if (!empty($tags_id_ar))
		{
			$tags_id = "{" . implode(",", $tags_id_ar) . "}";
		}
		
		$offset = ($page - 1) * self::$_limit;
		$limit = self::$_limit;
		
		/* Запрос общее кол-во результатов */
		$count = (int)G::db_core()->search_index_find_count($word, $tags_id)->single();
		
		/* Запрос */
		$result_sql = G::db_core()->search_index_find($word, $tags_id, $offset, $limit)->assoc();
		if (empty($result_sql))
		{
			return [];
		}
		
		/* Обработка результата */
		$result = [];
		foreach ($result_sql as $val)
		{
			/* Заголовок */
			$title = $val['Title'];
			$title = strip_tags($title);
			$title = str_replace(["\r", "\n", "\t"], " ", $title);
			while (strpos($title, "  ") !== false)
			{
				$title = str_replace("  ", " ", $title);
			}
			$title = trim($title);
			$title = mb_substr($title, 0, 250);
			
			/* Содержание */
			$content = $val['Content'];
			$content = strip_tags($content);
			$content = str_replace(["\r", "\n", "\t"], " ", $content);
			while (strpos($content, "  ") !== false)
			{
				$content = str_replace("  ", " ", $content);
			}
			$content = trim($content);
			$content = mb_substr($content, 0, 400);
			
			/* Результат */
			$result[] = 
			[
				"url" => $val['Url'],
				"title" => $title,
				"content" => $content,
				"tags" => $val['Tags']
			];
		}
		
		return 
		[
			"count" => $count,
			"result" => $result
		];
	}
	
	/**
	 * Поиск через Sphinx
	 * 
	 * @param array $word_ar
	 * @param array $tags_id_ar
	 * @param int $page
	 * @return array
	 */
	private static function _find_sphinx($word_ar, $tags_id_ar, $page)
	{
		/* Подготовка запроса */
		$word = "";
		if (!empty($word_ar))
		{
			$word = implode(" ", $word_ar);
		}
		
		$offset = ($page - 1) * self::$_limit;
		$limit = self::$_limit;
		
		/* Запрос */
		$sph = new SphinxClient();
		$sph->SetServer(P::get("_search", "sphinx_host"), P::get("_search", "sphinx_port"));
		$sph->SetMatchMode(SPH_MATCH_EXTENDED2);
		if (!empty($tags_id_ar))
		{
			$sph->SetFilter("tags_id", $tags_id_ar);
		}
		$sph->SetLimits($offset, $limit);
		$sph_result = $sph->Query($word, P::get("_search", "sphinx_index"));
		if($sph_result === false or empty($sph_result['matches']))
		{
			return []; 
		}
		
		/* Обработка результата */
		$result = [];
		foreach ($sph_result['matches'] as $val)
		{
			/* Заголовок */
			$title = $val['attrs']['title'];
			$title = strip_tags($title);
			$title = str_replace(["\r", "\n", "\t"], " ", $title);
			while (strpos($title, "  ") !== false)
			{
				$title = str_replace("  ", " ", $title);
			}
			$title = trim($title);
			$title = mb_substr($title, 0, 250);
			
			/* Содержание */
			$content = $val['attrs']['content'];
			$content = htmlspecialchars_decode($content);
			$content = strip_tags($content);
			$content = str_replace(["\r", "\n", "\t"], " ", $content);
			while (strpos($content, "  ") !== false)
			{
				$content = str_replace("  ", " ", $content);
			}
			$content = trim($content);
			$content = mb_substr($content, 0, 400);
			
			/* Результат */
			$result[] = 
			[
				"url" => $val['attrs']['url'],
				"title" => $title,
				"content" => $content,
				"tags" => $val['attrs']['tags']
			];
		}
		
		return 
		[
			"count" => $sph_result['total_found'],
			"result" => $result
		];
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
	
	/**
	 * Удалить ненужные символы с запроса
	 * 
	 * @param string $str
	 * @return string
	 */
	private static function _delete_bad_symbol($str, $tags_allow = true)
	{
		$str = mb_strtolower($str);
		$reg = "#[^0-9a-zа-яё\-\_\. ]#isu";
		if ($tags_allow === true)
		{
			$reg = "#[^0-9a-zа-яё\-\_\.\[\] ]#isu";
		}
		$str = preg_replace($reg, "", $str);
		
		while (strpos($str, "  ") !== false)
		{
			$str = str_replace("  ", " ", $str);
		}
		$str = trim($str);
		$str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
		$str = mb_substr($str, 0, 250);
		
		return $str;
	}
}
?>