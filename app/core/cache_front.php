<?php
/**
 * Кэш фронта
 */
class _Cache_Front
{
	/**
	 * Объект cache_route
	 * 
	 * @var _Cache
	 */
	public static $route;
	
	/**
	 * Объект cache_page
	 * 
	 * @var _Cache
	 */
	public static $page;
	
	/**
	 * Объект cache_ajax
	 * 
	 * @var _Cache
	 */
	public static $ajax;

	/**
	 * Сохранить маршрут в кэш
	 * 
	 * @param string $url_path
	 * @param array $in_filter
	 * @param string $in_serialize
	 * @param array $data
	 */
	public static function route_set($url_path, $in_filter, $in_serialize, $data)
	{
		/* Сохраняем фильтр входящих переменных урла */
		$key_in_filter = self::_route_key_in_filter($url_path);
		$in_filter['url_path'] = $url_path;
		if (self::$route->get($key_in_filter) === null)
		{
			self::$route->set($key_in_filter, $in_filter, "module_" . $data['module']);
		}
		
		/* Данные маршрута */
		$key_data = self::_route_key_data($url_path, $in_serialize);
		$data['url_path'] = $url_path;
		$data['in_serialize'] = $in_serialize;
		$data['mtime'] = self::_route_mtime($data['module']);			/* Время последней модификации файлов */
		
		/* Тэги */
		$tag = 
		[
			"module_" . $data['module'], 
			"exe_" . $data['module'] . "_" . $data['exe']
		];
		
		/* Сохраняем данные маршрута */
		self::$route->set($key_data, $data, $tag);
	}
	
	/**
	 * Получить фильтр для урлу
	 * 
	 * @param string $url_path
	 * @return array
	 */
	public static function route_get_in_filter($url_path)
	{
		return self::$route->get(self::_route_key_in_filter($url_path));
	}
	
	/**
	 * Получить данные по маршуруту
	 * 
	 * @param string $url_path
	 * @param string $in_serialize
	 * @return array
	 */
	public static function route_get_data($url_path, $in_serialize)
	{
		/* Данные по маршруту */
		$data = self::$route->get(self::_route_key_data($url_path, $in_serialize));
		if ($data === null)
		{
			return;
		}
		
		/* Проверяем дату последней модификации файлов */
		if ($data['mtime'] < self::_route_mtime($data['module']))
		{
			self::$route->delete_tag("module_" . $data['module']);
			self::$page->delete_tag("module_" . $data['module']);
			return;
		}
				
		/* Обозначаем что данные были из кэша */
		$data['cache_route'] = true;
		
		/* Данные по маршруту */
		return $data;
	}
	
	/**
	 * Создать кэш страницы
	 * 
	 * @param string $url_path
	 * @param array $in_filter
	 * @param string $in_serialize
	 * @param array $data
	 */
	public static function page_set($url_path, $in_filter, $in_serialize, &$data)
	{	
		/* Тэги */
		$tag = [];
		
		$tag[] = "module_" . $data['module_exe']['Identified'];
		$tag[] = "exe_" . $data['module_exe']['Identified'] . "_" . $data['exe']['Identified'];
		
		foreach ($data['inc'] as $v)
		{
			$tag[] = "inc_" . $v['Module_Identified'] . "_" . $v['Identified'];
			if (!in_array("module_" . $v['Module_Identified'], $tag))
			{
				$tag[] = "module_" . $v['Module_Identified'];
			}
		}
		
		$tag[] = "html_" . $data['html']['Identified'];
		
		foreach ($data['html_part'] as $v)
		{
			$tag[] = "html_part_" . $v;
		}
			
		/* Кэш для обычных страниц */
		if ($data['page_type'] === "module")
		{
			/* Записать фильтр входящих по exe */
			$key_in_filter = self::_page_key_exe_in_filter($data['module_exe']['Identified'], $data['exe']['Identified']);
			if (self::$page->get($key_in_filter) === null)
			{
				self::$page->set($key_in_filter, $in_filter, $tag);
			}

			/* Сохранить данные */
			$key_data = self::_page_key_data($url_path, $in_serialize);
			$data['url_path'] = $url_path;
			$data['in_serialze'] = $in_serialize;
			$mtime = self::_page_mtime($data);						/* Время последней модификации файлов */
			$data['mtime'] = $mtime;
			
			self::$page->set($key_data, $data, $tag);
		}
		/* Кэш для других страниц */
		elseif ($data['page_type'] === "home" or $data['page_type'] === "404" or $data['page_type'] === "403")
		{
			$key = self::_page_key_std($data['page_type']);
			$mtime = self::_page_mtime($data);						/* Время последней модификации файлов */
			$data['mtime'] = $mtime;
			self::$page->set($key, $data, $tag);
		}
	}
	
	/**
	 * Получить фильтр входящих переменных по исполнителю
	 * 
	 * @param string $module
	 * @param string $exe
	 * @return array
	 */
	public static function page_get_in_filter($module, $exe)
	{
		return self::$page->get(self::_page_key_exe_in_filter($module, $exe));
	}
	
	/**
	 * Получить данные по странице
	 * 
	 * @param string $url_path
	 * @param string $in_serialize
	 * @return array
	 */
	public static function page_get_data($url_path, $in_serialize)
	{
		/* Данные по странице */
		$data = self::$page->get(self::_page_key_data($url_path, $in_serialize));
		if ($data === null)
		{
			return;
		}
		
		/* Проверить дату последней модификации файлов */
		if (self::_page_mtime_check($data['mtime'], self::_page_mtime($data)) === false)
		{
			return;
		}
		
		return $data;
	}
	
	/**
	 * Получить данные по другой странице
	 * 
	 * @param string $name
	 * @return array
	 */
	public static function page_get_data_other($name)
	{
		/* Данные по странице */
		$data = self::$page->get(self::_page_key_std($name));
		if ($data === null)
		{
			return;
		}
		
		/* Проверить дату последней модификации файлов */
		if (self::_page_mtime_check($data['mtime'], self::_page_mtime($data)) === false)
		{
			return;
		}
		
		return $data;
	}
	
	/**
	 * Создать кэш аякс
	 * 
	 * @param string $module
	 * @param string $ajax
	 * @param array $in_filter
	 * @param string $in_serialize
	 * @param string $data
	 */
	public static function ajax_set($module, $ajax, $in_filter, $in_serialize, &$data)
	{
		/* Тэги */
		$tag = 
		[
			"module_" . $module,
			"ajax_" . $module . "_" . $ajax
		];
		
		/* Сохраняем фильтр входящих переменных */
		$key_in_filter = self::_ajax_key_in_filter($module, $ajax);
		if (self::$ajax->get($key_in_filter) === null)
		{
			self::$ajax->set($key_in_filter, $in_filter, $tag);
		}
		
		/* Сохраняем данные */
		$key_data = self::_ajax_key_data($module, $ajax, $in_serialize);
		$data['in_serialize'] = $in_serialize;
		$mtime = self::_ajax_mtime($data);			/* Время последней модификации файлов */
		$data['mtime'] = $mtime;
		self::$ajax->set($key_data, $data, $tag);
	}
	
	/**
	 * Получить фильтр входящих переменных по аяксу
	 * 
	 * @param string $module
	 * @param string $ajax
	 * @return array
	 */
	public static function ajax_get_in_filter($module, $ajax)
	{
		return self::$ajax->get(self::_ajax_key_in_filter($module, $ajax));
	}
	
	/**
	 * Получить данные по аяксу
	 * 
	 * @param string $module
	 * @param string $ajax
	 * @param string $in_serialize
	 * @return array
	 */
	public static function ajax_get_data($module, $ajax, $in_serialize)
	{
		/* Данные по аяксу */
		$data = self::$ajax->get(self::_ajax_key_data($module, $ajax, $in_serialize));
		if ($data === null)
		{
			return;
		}
		
		/* Проверяем время последней модификации файлов */
		if ($data['mtime'] < self::_ajax_mtime($data))
		{
			self::$ajax->delete_tag("ajax_" . $data['module'] . "_" . $data['ajax']);
			return;
		}
		
		return $data;
	}

	/**
	 * Получить сведения по маршрутам
	 * 
	 * @param string $param
	 * @return array
	 */
	public static function info($param = null)
	{
		/* Параметры */
		$search_module = ""; $search_exe = ""; $search_url_path = "";
		if ($param !== null)
		{
			if (isset($param['module']))
			{
				$search_module = $param['module'];
			}
			elseif (isset($param['exe']))
			{
				$search_exe = $param['exe'];
			}
			elseif (isset($param['url_path']))
			{
				$search_url_path = $param['url_path'];
			}
		}
		
		/* Все ключи */
		$key_all = self::$route->get_key_all()['cache'];
		
		/* Собрать урлы с фильтром */
		$url_all = [];
		foreach ($key_all as $val)
		{
			if (substr($val, 0, 10) === "in_filter_")
			{
				$in_filter = self::$route->get($val);
				
				/* Поиск по урлу */
				if ($search_url_path !== "" and mb_strpos($in_filter['url_path'], $search_url_path) === false)
				{
					continue;
				}
				
				$one = [];
				$one['url_path'] = $in_filter['url_path'];
				unset($in_filter['url_path']);
				$one['route_in_filter'] = $in_filter;	
				$one['route_data'] = [];
				
				$url_all[] = $one;
			}
		}
		
		/* Собрать данные маршрута по урлу */
		foreach ($key_all as $val)
		{
			if (substr($val, 0, 5) === "data_")
			{
				$data = self::$route->get($val);				
				$url_key = array_search($data['url_path'], array_column($url_all, "url_path"));
				if ($url_key !== false)
				{
					if ($param !== null)
					{
						$tag = self::$route->get_tag($val);
						
						/* Поиск по модулю */
						if ($search_module !== "" and !in_array("module_" . $search_module, $tag))
						{
							continue;
						}
						/* Поиск по exe */
						elseif ($search_exe !== "" and !in_array("exe_" . $search_exe, $tag))
						{
							continue;
						}
					}
					
					/* Добавляем */
					unset($data['url_path']);
					$url_all[$url_key]['route_data'][] = $data;
				}
			}
		}
		
		/* Удалить урлы, у которых нет данных (не прошли фильтрацию) */
		foreach ($url_all as $key => $val)
		{
			if (empty($val['route_data']))
			{
				unset($url_all[$key]);
			}
		}
		sort($url_all, SORT_NUMERIC);
		
		/* Добавить кэши страниц по урлу */
		$page_key_all = self::$page->get_key_all()['cache'];
		foreach ($page_key_all as $val)
		{
			if (substr($val, 0, 5) === "data_")
			{
				$data = self::$page->get($val);
				if (!isset($data['url_path']))
				{
					continue;
				}
				
				/* Поиск по модулю */
				if ($search_module !== "" and $search_exe !== $data['module_exe']['Identified'])
				{
					continue;
				}
				/* Поиск по exe */
				elseif ($search_exe !== "" and $search_exe !== $data['module_exe']['Identified'] . "_" . $data['exe']['Identified'])
				{
					continue;
				}
				
				$url_key = array_search($data['url_path'], array_column($url_all, "url_path"));
				if ($url_key !== false)
				{
					$url_all[$url_key]['page_data'][] = 
					[
						"in_serialize" => $data['in_serialze'],
						"module" => $data['module_exe']['Identified'],
						"exe" => $data['exe']['Identified']
					];
				}
			}
		}
		
		return $url_all;
	}
	
	/**
	 * Удалить маршрут
	 * 
	 * @param array $param
	 */
	public static function delete($param)
	{
		/* Параметры */
		if (empty($param))
		{
			throw new Exception("Укажите параметры для удаления.");
		}
		
		/* Удалить у других */
		if (in_array($param, ["home", "404", "403"]))
		{
			self::$page->delete(self::_page_key_std($param));
		}
		
		/* Удалить по модулю */
		if (!empty($param['module']))
		{
			self::$route->delete_tag("module_" . $param['module']);
			self::$page->delete_tag("module_" . $param['module']);
			self::$ajax->delete_tag("module_" . $param['module']);
		}
		/* Удалить по исполнителю */
		elseif (!empty($param['exe']))
		{
			self::$route->delete_tag("exe_" . $param['exe']);
			self::$page->delete_tag("exe_" . $param['exe']);
		}
		/* Удалить по инку */
		elseif (!empty ($param['inc']))
		{
			self::$page->delete_tag("inc_" . $param['inc']);
		}
		/* Удалить по html */
		elseif (!empty ($param['html']))
		{
			self::$page->delete_tag("html_" . $param['html']);
		}
		/* Удалить по кускам шаблнона */
		elseif (!empty ($param['html_part']))
		{
			self::$page->delete_tag("html_part_" . $param['html_part']);
		}
		/* Удалить по аяксу */
		elseif (!empty($param['ajax']))
		{
			self::$ajax->delete_tag("ajax_" . $param['ajax']);
		}
		/* Удалить по урлу */
		elseif (!empty ($param['url_path']))
		{
			/* Удалить из кэша роутера */
			$route_key_all = self::$route->get_key_all()['cache'];
			foreach ($route_key_all as $val)
			{
				if (substr($val, 0, 10) === "in_filter_" or substr($val, 0, 5) === "data_")
				{
					$data = self::$route->get($val);
					
					if (mb_strpos($data['url_path'], $param['url_path']) !== false)
					{
						self::$route->delete($val);
					}
				}
			}
			
			/* Удалить из кэша страниц */
			$page_key_all = self::$page->get_key_all()['cache'];
			foreach ($page_key_all as $val)
			{
				if (substr($val, 0, 5) === "data_")
				{
					$data = self::$page->get($val);
					
					if (mb_strpos($data['url_path'], $param['url_path']) !== false)
					{
						self::$page->delete($val);
					}
				}
			}
		}
	}
	
	/**
	 * Очистить кэш фронта
	 */
	public static function truncate()
	{
		self::$route->truncate();
		self::$page->truncate();
		self::$ajax->truncate();
	}

	/**
	 * Получить наименование ключа содержащего фильтр входящих переменных
	 * 
	 * @param string $url_path
	 * @return string
	 */
	private static function _route_key_in_filter($url_path)
	{
		return "in_filter_" . md5($url_path);
	}
	
	/**
	 * Получить наименование ключа содержащего данные по маршруту
	 * 
	 * @param string $url_path
	 * @param string $in_serialize
	 * @return string
	 */
	private static function _route_key_data($url_path, $in_serialize)
	{
		return "data_" . md5($url_path . $in_serialize);
	}
	
	/**
	 * Получить дату последней модификации файлов влияющих на роутер (route.php)
	 * 
	 * @param type $module
	 * @return int
	 */
	private static function _route_mtime($module)
	{
		return filemtime(DIR_APP . "/" . _Module::get_type_by_identified($module) . "/" . $module . "/route.php");
	}

	/**
	 * Получить наименование ключа содержащего фильт входящих переменных по исполнителю
	 * 
	 * @param string $module
	 * @param string $exe
	 * @return string
	 */
	private static function _page_key_exe_in_filter($module, $exe)
	{
		return "exe_in_filter_" . $module . "_" . $exe;
	}
	
	/**
	 * Получить наименование ключа содержащего данные по странице
	 * 
	 * @param string $url_path
	 * @param string $in_serialize
	 * @return string
	 */
	private static function _page_key_data($url_path, $in_serialize)
	{
		return "data_" . md5($url_path . $in_serialize);
	}
	
	/**
	 * Получить наименование ключа содержащего данные по другим страницам (home, 404, 403)
	 * 
	 * @param string $name
	 * @return string
	 */
	private static function _page_key_std($name)
	{
		if (!in_array($name, ["home", "404", "403"]))
		{
			throw new Exception("Наименование другой страницы задано неверно.");
		}
		
		return "other_" . $name;
	}

	/**
	 * Получить даты последеней модификации файлов влияющих на страницу (exe, inc, html, html_part)
	 * 
	 * @param array $data
	 * @return array
	 */
	private static function _page_mtime(&$data)
	{
		$mtime = [];		/* Массив с датами модификации файлов */
		
		/* Исполнитель */
		$mtime['exe'] = [];
		$exe_dir = DIR_APP . "/" . _Module::get_type_by_identified($data['module_exe']['Identified']) . "/" . $data['module_exe']['Identified'] . "/exe";
		$exe_mtime_act = filemtime($exe_dir . "/act/" . $data['exe']['Identified'] . ".php");
		$exe_mtime_html = filemtime($exe_dir . "/html/" . $data['exe']['Identified'] . ".html");
		$mtime['exe']['name'] = $data['module_exe']['Identified'] . "_" . $data['exe']['Identified'];
		$mtime['exe']['mtime'] = $exe_mtime_act > $exe_mtime_html ? $exe_mtime_act : $exe_mtime_html;
		
		/* Инки */
		$mtime['inc'] = [];
		foreach ($data['inc'] as $v)
		{
			$inc_dir = DIR_APP . "/" . _Module::get_type_by_identified($v['Module_Identified']) . "/" . $v['Module_Identified'] . "/inc";
			$inc_mtime_act = filemtime($inc_dir . "/act/" . $v['Identified'] . ".php");
			$inc_mtime_html = filemtime($inc_dir . "/html/" . $v['Identified'] . ".html");
			$mtime['inc'][] =
			[
				"name" => $v['Module_Identified'] . "_" . $v['Identified'],
				"mtime" => $inc_mtime_act > $inc_mtime_html ? $inc_mtime_act : $inc_mtime_html
			];
		}
		
		/* Шаблон */
		$mtime['html'] = [];
		$mtime['html']['identified'] = $data['html']['Identified'];
		$mtime['html']['mtime'] = filemtime(DIR_APP . "/html/" . $data['html']['Identified'] . ".html");
		
		/* Куски шаблона */
		$mtime['html_part'] = [];
		foreach ($data['html_part'] as $v)
		{
			if (!is_file(DIR_APP . "/html/part/" . $v . ".html"))
			{
				continue;
			}
			
			$mtime['html_part'][] = 
			[
				"identified" => $v,
				"mtime" => filemtime(DIR_APP . "/html/part/" . $v . ".html")
			];
		}
		
		return $mtime;
	}
	
	/**
	 * Проверить время модификации файлов влияющих на страницу
	 * 
	 * @param array $old
	 * @param array $new
	 * @return boolean
	 */
	private static function _page_mtime_check($old, $new)
	{	
		/* Полное совпадение */
		if ($old === $new)
		{
			return true;
		}
		
		$is_modify = false;						/* Менялись ли файлы */
		
		/* Исполнители */
		if ($old['exe'] !== $new['exe'])
		{
			self::$route->delete_tag("exe_" . $old['exe']['name']);
			self::$page->delete_tag("exe_" . $old['exe']['name']);
			$is_modify = true;
		}
		
		/* Инки */
		foreach ($old['inc'] as $o)
		{
			$inc_mtime_check = false;
			foreach ($new['inc'] as $n)
			{
				if ($o['name'] === $n['name'] and $o['mtime'] === $n['mtime'])
				{
					$inc_mtime_check = true;
					break;
				}
			}
			
			/* Файл инка был изменён */
			if ($inc_mtime_check === false)
			{
				self::$page->delete_tag("inc_" . $o['name']);
				$is_modify = true;
			}
		}
		
		/* Шаблон */
		if ($old['html'] !== $new['html'])
		{
			self::$page->delete_tag("html_" . $old['html']['identified']);
			$is_modify = true;
		}
		
		/* Куски шаблона */
		foreach ($old['html_part'] as $o)
		{
			$html_part_mtime_check = false;
			foreach ($new['html_part'] as $n)
			{
				if ($o['identified'] === $n['identified'] and $o['mtime'] === $n['mtime'])
				{
					$html_part_mtime_check = true;
					break;
				}
			}
			
			/* Файл был изменён */
			if ($html_part_mtime_check === false)
			{
				self::$page->delete_tag("html_part_" . $o['identified']);
				$is_modify = true;
			}
		}
		
		/* Если файлы изменились, то проверка времени модификации не пройдена */
		if ($is_modify === true)
		{
			return false;
		}
		elseif ($is_modify === false)
		{
			return true;
		}
	}

	/**
	 * Получить наименование ключа содержащего фильтр входящих переменных
	 * 
	 * @param string $module
	 * @param string $ajax
	 * @return string
	 */
	private static function _ajax_key_in_filter($module, $ajax)
	{
		return "in_filter_" . md5($module . $ajax);
	}
	
	/**
	 * Получить наименование ключа содержащего данные по маршруту
	 * 
	 * @param string $module
	 * @param string $ajax
	 * @param string $in_serialize
	 * @return string
	 */
	private static function _ajax_key_data($module, $ajax, $in_serialize)
	{
		return "data_" . md5($module . $ajax . $in_serialize);
	}
	
	/**
	 * Получить время последней модификации файлов влияющих на аякс
	 * 
	 * @param array $data
	 * @return int
	 */
	private static function _ajax_mtime(&$data)
	{
		$file = [];			/* Файлы влияющие на аякс */
		
		$dir = $exe_dir = DIR_APP . "/" . $data['module_type'] . "/" . $data['module'] . "/ajax/" . $data['data_type'];
		
		/* Файлы влияющие на аякс */
		switch ($data['data_type'])
		{
			case "json":
			{
				$file[] = $dir . "/" . $data['ajax'] . ".php";
			}
			break;
		
			case "html":
			{
				$file[] = $dir . "/act/" . $data['ajax'] . ".php";
				$file[] = $dir . "/html/" . $data['ajax'] . ".html";
				
				if (is_file($dir . "/css/" . $data['ajax'] . ".css"))
				{
					$file[] = $dir . "/css/" . $data['ajax'] . ".css";
				}
				
				if (is_file($dir . "/js/" . $data['ajax'] . ".js"))
				{
					$file[] = $dir . "/js/" . $data['ajax'] . ".js";
				}
			}
			break;
		
			case "text":
			{
				$file[] = $dir . "/" . $data['ajax'] . ".php";
			}
			break;
		
			case "json_page":
			{
				if ($_SERVER['REQUEST_METHOD'] === "GET")
				{
					$file[] = $dir . "/get/" . $data['ajax'] . ".php";
					if (is_file($dir . "/html/" . $data['ajax'] . ".html"))
					{
						$file[] = $dir . "/html/" . $data['ajax'] . ".html";
					}
				}
				elseif ($_SERVER['REQUEST_METHOD'] === "POST")
				{
					$file[] = $dir . "/post/" . $data['ajax'] . ".php";
				}
				
				if (is_file($dir . "/css/" . $data['ajax'] . ".css"))
				{
					$file[] = $dir . "/css/" . $data['ajax'] . ".css";
				}
				
				if (is_file($dir . "/js/" . $data['ajax'] . ".js"))
				{
					$file[] = $dir . "/js/" . $data['ajax'] . ".js";
				}
			}
			break;
		}
		
		/* Время последней модификации файлов */
		$mtime = 0;
		foreach ($file as $v)
		{
			$mtime_file = filemtime($v);
			$mtime = $mtime_file > $mtime ? $mtime_file : $mtime;
		}
		
		return $mtime;
	}
}
?>