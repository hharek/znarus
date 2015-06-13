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
	 * Сохранить маршрут в кэш
	 */
	public static function route_set($url_path, $in_filter, $in_serialize, $data)
	{
		/* Проверяем дату последней модификации файла route.php */
		self::_route_mtime_check($data['module']);
		
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
		
		/* Сохраняем данные маршрута */
		self::$route->set($key_data, $data, ["module_" . $data['module'], "exe_" . $data['module'] . "_" . $data['exe']]);
	}
	
	/**
	 * Получить фильтр для урлу
	 * 
	 * @param string $url_path
	 * @return array
	 */
	public static function route_get_in_filter($url_path)
	{
		$key_in_filter = self::_route_key_in_filter($url_path);
		$in_filter = self::$route->get($key_in_filter);
		if ($in_filter === null)
		{
			return;
		}
		
		return $in_filter;
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
		$key = self::_route_key_data($url_path, $in_serialize);
		$data = self::$route->get($key);
		if ($data === null)
		{
			return;
		}
		
		/* Проверяем дату последней модификации файла route.php */
		if (self::_route_mtime_check($data['module']) === false)
		{
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
	 * @param string $data
	 */
	public static function page_set($url_path, $in_filter, $in_serialize, $data)
	{
		/* Проверить время изменения */
		self::_page_mtime_exe_check($data['module_exe']['Identified'], $data['exe']['Identified']);
		
		/* Тэги */
		$tag = 
		[
			"module_" . $data['module_exe']['Identified'],
			"exe_" . $data['module_exe']['Identified'] . "_" . $data['exe']['Identified']
		];
			
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
			self::$page->set($key_data, $data, $tag);
		}
		/* Кэш для других страниц */
		elseif ($data['page_type'] === "home" or $data['page_type'] === "404" or $data['page_type'] === "403")
		{
			$key = self::_page_key_std($data['page_type']);
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
		/* Фильтр */
		$key_in_filter = self::_page_key_exe_in_filter($module, $exe);
		$in_filter = self::$page->get($key_in_filter);
		if ($in_filter === null)
		{
			return;
		}
		
		/* Проверить дату изменения файлов php, html у исполнителя */
		if (self::_page_mtime_exe_check($module, $exe) === false)
		{
			return;
		}
		
		return $in_filter;
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
		$key_data = self::_page_key_data($url_path, $in_serialize);
		return self::$page->get($key_data);
	}
	
	/**
	 * Получить данные по другой странице
	 * 
	 * @param string $name
	 * @return array
	 */
	public static function page_get_data_other($name)
	{
		$key_data = self::_page_key_std($name);
		return self::$page->get($key_data);
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
		$key_all = self::$route->get_key_all("cache");
		
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
		$page_key_all = self::$page->get_key_all("cache");
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
		
		/* Удалить по модулю */
		if ($search_module !== "")
		{
			self::$route->delete_tag("module_" . $search_module);
			self::$page->delete_tag("module_" . $search_module);
		}
		/* Удалить по исполнителю */
		elseif ($search_exe !== "")
		{
			self::$route->delete_tag("exe_" . $search_exe);
			self::$page->delete_tag("exe_" . $search_exe);
		}
		/* Удалить по урлу */
		elseif ($search_url_path !== "")
		{
			/* Удалить из кэша роутера */
			$route_key_all = self::$route->get_key_all("cache");
			foreach ($route_key_all as $val)
			{
				if (substr($val, 0, 10) === "in_filter_" or substr($val, 0, 5) === "data_")
				{
					$data = self::$route->get($val);
					
					if (mb_strpos($data['url_path'], $search_url_path) !== false)
					{
						self::$route->delete($val);
					}
				}
			}
			
			/* Удалить из кэша страниц */
			$page_key_all = self::$page->get_key_all("cache");
			foreach ($page_key_all as $val)
			{
				if (substr($val, 0, 5) === "data_")
				{
					$data = self::$page->get($val);
					
					if (mb_strpos($data['url_path'], $search_url_path) !== false)
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
	 * @param strint $url_path
	 * @param string $in_serialize
	 * @return string
	 */
	private static function _route_key_data($url_path, $in_serialize)
	{
		return "data_" . md5($url_path . $in_serialize);
	}
	
	/**
	 * Проверить дату последней модификации route.php и данных в кэше и назначить новые данные
	 * 
	 * @param string $module
	 */
	private static function _route_mtime_check($module)
	{
		/* Дата изменения route.php */
		$module_mtime = filemtime(DIR_APP . "/" . _Module::get_type_by_identified($module) . "/" . $module . "/route.php");
		
		/* Дата изменения route.php в кэше */
		$module_mtime_cache = self::$route->get("module_mtime_" . $module);
		if ($module_mtime_cache === null)
		{
			self::$route->set("module_mtime_" . $module, $module_mtime, "module_" . $module);
			return true;
		}
		
		/* route.php изменилься */
		if ($module_mtime_cache !== $module_mtime)
		{
			self::delete(["module" => $module]);												/* Удаляем старые кэши */
			self::$route->set("module_mtime_" . $module, $module_mtime, "module_" . $module);	/* Назначаем новое время */
			
			return false;
		}
		/* route.php не изменилься */
		else
		{
			return true;
		}
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
	 * Проверить дату изменения файлов php, html у исполнителя
	 * 
	 * @param string $module
	 * @param string $exe
	 */
	private static function _page_mtime_exe_check($module, $exe)
	{
		/* Дата последеней модификации */
		$act_mtime = filemtime(DIR_APP . "/" . _Module::get_type_by_identified($module) . "/" . $module . "/exe/act/" . $exe . ".php");
		$html_mtime = filemtime(DIR_APP . "/" . _Module::get_type_by_identified($module) . "/" . $module . "/exe/html/" . $exe . ".html");
		$mtime = $act_mtime;
		if ($html_mtime > $act_mtime)
		{
			$mtime = $html_mtime;
		}
		
		$tag = 
		[
			"module_" . $module,
			"exe_" . $module . "_" . $exe
		];
		
		/* Дата изменения в кэше */
		$mtime_cache = self::$page->get("mtime_exe_" . $module . "_" . $exe);
		if ($mtime_cache === null)
		{
			self::$page->set("mtime_exe_" . $module . "_" . $exe, $mtime, $tag);
			return true;
		}
		
		/* Файлы изменились */
		if ($mtime !== $mtime_cache)
		{
			self::delete(["module" => $module]);										/* Удаляем старые кэши */
			self::$page->set("mtime_exe_" . $module . "_" . $exe, $mtime, $tag);		/* Назначаем новое время */
			
			return false;
		}
		/* Файлы не изменилься */
		else
		{
			return true;
		}
	}
}
?>