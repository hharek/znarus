<?php
/**
 * Страницы
 */
class _Page extends TM
{
	protected static $_name = "Страницы";
	protected static $_schema = "core";
	protected static $_table = "page";
	protected static $_field = 
	[
		[
			"identified" => "ID",
			"name" => "Порядковый номер",
			"type" => "id"
		],
		[
			"identified" => "Name",
			"name" => "Наименование",
			"type" => "string",
			"unique" => true,
			"unique_key" => "UN_Name",
			"order" => "asc"
		],
		[
			"identified" => "Url",
			"name" => "Урл",
			"type" => "url_part",
			"unique" => true,
			"unique_key" => "UN_Url"
		],
		[
			"identified" => "Content",
			"name" => "Содержимое",
			"type" => "html",
			"empty_allow" => true
		],
		[
			"identified" => "Parent",
			"name" => "Корень",
			"type" => "int",
			"foreign" => 
			[
				"class" => "_Page",
				"schema" => "core",
				"table" => "page",
				"field" => "ID"
			],
			"unique" => true,
			"unique_key" => ["UN_Name", "UN_Url"],
			"default" => null,
			"null" => true
		],
		[
			"identified" => "Tags",
			"name" => "Тэги",
			"type" => "tags",
			"require" => false,
			"empty_allow" => true
		],
		[
			"identified" => "Html_ID",
			"name" => "Привязка к основному шаблону",
			"type" => "uint",
			"foreign" => 
			[
				"class" => "_Html",
				"schema" => "core",
				"table" => "html",
				"field" => "ID",
				"type" => "null"
			],
			"default" => null,
			"null" => true
		],
		[
			"identified" => "Last_Modified",
			"name" => "Дата последнего изменения",
			"type" => "last_modified"
		],
		[
			"identified" => "Meta_Title",
			"name" => "Тэг title",
			"type" => "string",
			"null" => true,
			"empty_allow" => true,
			"require" => false
		],
		[
			"identified" => "Meta_Description",
			"name" => "Тэг meta name=description",
			"type" => "text",
			"empty_allow" => true,
			"require" => false
		],
		[
			"identified" => "Meta_Keywords",
			"name" => "Тэг meta name=keywords",
			"type" => "text",
			"empty_allow" => true,
			"require" => false
		],
		[
			"identified" => "Active",
			"name" => "Активность",
			"type" => "boolean",
			"default" => true,
			"require" => false
		]
	];
	
	/**
	 * Всё страницы
	 * 
	 * @var array
	 */
	private static $_page = [];
	
	/**
	 * Добавить
	 * 
	 * @param array $data
	 * @return array
	 */
	public static function add (array $data) : array
	{
		if (P::get("_page", "url_auto") and empty($data['Url']))
		{
			$data['Url'] = self::generate_url();
		}
		
		/* Удалить кэш */
		Cache::delete(["module" => "_page"]);
		
		return static::insert($data);
	}
	
	/**
	 * Править
	 * 
	 * @param array $data
	 * @param int $id
	 * @return array
	 */
	public static function edit (array $data, int $id) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "_page"]);
		
		/* Last_Modified */
		$data['Last_Modified'] = "now";
		
		return static::update($data, $id);
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function remove (int $id) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "_page"]);
		
		return static::delete($id);
	}
	
	/**
	 * Сгенерировать урл
	 * 
	 * @return string
	 */
	public static function generate_url () : string
	{
		$url = md5(microtime() . mt_rand(1, 1000000));
		$url = substr($url, 0, P::get("_page", "url_auto_length"));
		
		try
		{
			_Page::unique(["Url" => $url], null, true);
		} 
		catch (Exception $e) 
		{
			return self::generate_url();
		}
		
		return $url;
	}
	
	/**
	 * Получить подчинённые страницы
	 * 
	 * @param int $parent
	 */
	public static function child (int $parent) : array
	{
		if ($parent === 0)
		{
			$parent = null;
		}
		
		if (empty(self::$_page))
		{
			self::$_page = static::selectl();
		}
		
		$child = [];
		foreach (self::$_page as $p)
		{
			if ($parent === $p['Parent'])
			{
				$child[] = $p;
			}
		}
		
		return  $child;
	}
	
	/**
	 * Путь до страницы
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function path (int $id) : array
	{
		$path = self::_path($id);
		$path = array_reverse($path);
		
		return $path;
	}
	
	/**
	 * Получить все доступные урлы, если урл иерархический
	 * 
	 * @param int $parent
	 * @param string $parent_url
	 * @return array
	 */
	public static function url_all_hierarchy (int $parent = 0, string $parent_url = "/") : array
	{
		if ($parent === 0)
		{
			$parent = null;
		}
		
		if ($parent_url !== "/")
		{
			$parent_url .= "/";
		}
		
		if (empty(self::$_page))
		{
			self::$_page = static::selectl();
		}
		
		$url = [];
		foreach (self::$_page as $p)
		{
			if ($parent === $p['Parent'])
			{
				$url[] = 
				[
					"id" => $p['ID'],
					"url" => $parent_url . $p['Url'],
					"active" => $p['Active']
				];
			}
		}

		$url_all = [];
		foreach ($url as $u)
		{
			$url_all[] = $u;
			$url_all = array_merge($url_all, self::url_all_hierarchy($u['id'], $u['url']));
		}
		
		return  $url_all;
	}
	
	/**
	 * Получить все доступные урлы
	 * 
	 * @return array
	 */
	public static function url_all () : array
	{
		if (empty(self::$_page))
		{
			self::$_page = static::selectl();
		}
		
		$prefix = "";
		if (P::get("_page", "url_auto"))
		{
			$prefix = P::get("_page", "url_auto_prefix");
		}
		
		$url_all = [];
		foreach (self::$_page as $p)
		{
			$url_all[] = 
			[
				"id" => $p['ID'],
				"url" => "/" . $prefix . $p['Url'],
				"active" => $p['Active']
			];
		}
		
		return $url_all;
	}
	
	/**
	 * Получить полный урл по странице
	 * 
	 * @param int $id
	 * @return string
	 */
	public static function full_url (int $id) : string
	{
		$url = "/";
		if (P::get("_page", "url_hierarchy"))
		{
			$path = self::path($id);
			$url .= implode("/", array_column($path, "Url"));
		}
		else
		{
			$prefix = "";
			if (P::get("_page", "url_auto"))
			{
				$prefix = P::get("_page", "url_auto_prefix");
			}
			
			$page = _Page::get($id);
			$url .= $prefix . $page['Url'];
		}
		
		return $url;
	}
	
	/**
	 * Получить информацию по странице
	 * 
	 * @param mixed $param
	 * @return array
	 */
	public static function page_info ($param = null)
	{
		/* Главная страница модуля «Страницы» */
		if (empty($param))
		{
			
			return
			[
				"title" => "Страницы",
				"disable" => true,
				"child" => self::_page_info_child(0)
			];
		}
		
		/* Все другие страницы */
		if (!empty($param['id']))
		{
			/* Сведения по странице */
			$page = self::get($param['id']);
			
			/* Урл по странице */
			$url = self::full_url($page['ID']);
			
			/* Данные */
			return
			[
				"url" => $url . URL_END,
				"title" => $page['Name'],
				"content" => $page['Content'],
				"tags" => $page['Tags'],
				"last_modified" => $page['Last_Modified'],
				"child" => self::_page_info_child($page['ID'])
			];
		}
	}
	
	/**
	 * Получить путь по странице (перевёрнуто)
	 * 
	 * @param int $id
	 * @return array
	 */
	private static function _path (int $id) : array
	{
		if (empty(self::$_page))
		{
			self::$_page = static::selectl();
		}
		
		$path = [];
		foreach (self::$_page as $p)
		{
			if ((int)$p['ID'] === $id)
			{
				$path[] = $p;
				if ($p['Parent'] !== null)
				{
					$path = array_merge($path, self::_path($p['Parent']));
				}
			}
		}
		
		return $path;
	}
	
	/**
	 * Получить подчинённые страницы для Page Info
	 * 
	 * @param int $parent
	 * @return array
	 */
	private static function _page_info_child(int $parent) : array
	{
		$child = self::child($parent);
		
		$child_page_info = [];
		foreach ($child as $c)
		{
			if ($c['Active'] === false)
			{
				continue;
			}
			
			$child_page_info[] = 
			[
				"url" => self::full_url($c['ID']),
				"title" => $c['Name'],
				"param" => 
				[
					"id" => $c['ID']
				]
			];
		}
		
		return $child_page_info;
	}
}
?>