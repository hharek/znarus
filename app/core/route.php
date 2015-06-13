<?php
/**
 * Маршрутизатор
 */
class _Route
{
	/**
	 * Найти маршрут
	 * 
	 * @param string $url
	 * @return array
	 */
	public static function get()
	{
		/* Данные по маршруту */
		$data = null;
		
		/* Главная страница */
		if ($data === null)
		{
			if (G::url_path() === "/")
			{
				$data = self::std("home");
			}
		}
		
		/* Если урл неправильный */
		if ($data === null)
		{
			if (_Front::$_url_correct === false)
			{
				$data = self::std("404");
			}
		}
		
		/* Ищем маршрут в кэше */
		if ($data === null)
		{
			$data = self::_cache_get();
		}
		
		/* Ищем маршрут через модули */
		if ($data === null)
		{
			$data = self::_module();
		}
		
		/* Если маршрут не найден то 404 */
		if ($data === null)
		{
			$data = self::std("404");
		}
		
		/* Записать маршрут в кэш */
		
		self::_cache_set($data);
		
		/* Вернуть маршрут */
		return $data;
	}
	
	/**
	 * Стандартные маршруты
	 * 
	 * @param string $page_type
	 * @return array
	 */
	public static function std($page_type)
	{
		switch ($page_type)
		{
			/* Главная страница */
			case "home":
			{
				return
				[
					"page_type" => "home",
					"module" => P::get("home_module"),
					"exe" => P::get("home_exe")
				];
			}
			break;
		
			/* Страница 404 */
			case "404":
			{
				return
				[
					"page_type" => "404",
					"module" => P::get("404_module"),
					"exe" => P::get("404_exe")
				];
			}
			break;
		
			/* Страница 403 */
			case "403":
			{
				return 
				[
					"page_type" => "403",
					"module" => P::get("403_module"),
					"exe" => P::get("403_exe")
				];
			}
			break;
		
			/* Модуль */
			case "module":
			{
				throw new Exception("Невозможно получить стандартный маршрут по странице типа «module».");
			}
			break;
		
			/* Неизвестный тип страницы */
			default :
			{
				throw new Exception("Тип страницы указан неверно.");
			}
			break;
		}
	}
	
	/**
	 * Найти маршрут используя модули
	 * 
	 * @return array
	 */
	private static function _module()
	{
		/* Все активные модули */
		$module_all = _Module::get_by_type("all", 1);
		
		$data = null;											/* Данные по маршруту */
		$global_key = G::_all(true);		$global_key_new = [];	/* Наименование глобальных переменных определённые в route.php */
		foreach ($module_all as $module)
		{
			/* Отсутствует файл route.php */
			if (!is_file(DIR_APP . "/" . $module['Type'] . "/" . $module['Identified'] . "/route.php"))
			{
				continue;
			}
			
			/* Удаляем глобальные переменные определённые в предыдушем route.php */		
			foreach ($global_key_new as $val)
			{
				G::_unset($val);
			}
			
			/* Сбрасываем фильтр входящих переменных урла */
			_Front::$_route_in_filter = [];
			
			/* Выполняем route.php */
			$return = call_user_func(function($_module)
			{
				return require DIR_APP . "/" . $_module['Type'] . "/" . $_module['Identified'] . "/route.php";
			}, $module);
			
			/* Новые глобальные переменные */
			$global_key_new = array_diff(G::_all(true), $global_key);
			
			/* route.php должен вернуть идентификатор exe */
			if (!empty($return) and is_string($return))
			{
				/* Данные маршрута */
				$data = 
				[
					"page_type" => "module",
					"module" => $module['Identified'],
					"exe" => $return
				];
				
				/* Поместить глобальные переменные в данные по маршруту */
				$global_new = [];
				foreach ($global_key_new as $val)
				{
					$global_new[$val] = G::_get($val);
				}
				
				if (!empty($global_new))
				{
					$data['global'] = $global_new;
				}
				
				break;
			}
		}
		
		return $data;
	}
	
	/**
	 * Записать маршрут в кэш
	 */
	private static function _cache_set($data)
	{
		/* Не сохраняем маршрут главной страницы */
		if ($data['page_type'] === "home") 
		{
			return;
		}
		
		/* Не сохраняем если маршрут получен из кэша */
		if (!empty($data['cache_route']))
		{
			return;
		}
		
		/* Исполнитель */
		$exe = _Exe::get_by_identified($data['module'], $data['exe']);
		
		/* Сохраняем маршрут в кэш */
		if (CACHE_ROUTE_ENABLE === true and (bool)$exe['Cache_Route'] === true)
		{
			_Cache_Front::route_set(G::url_path(), _Front::$_route_in_filter, _Front::_in_serialize(_Front::$_route_in_filter), $data);
		}
	}
	
	/**
	 * Найти маршрут в кэше
	 * 
	 * @return array
	 */
	private static function _cache_get()
	{
		/* Не искать если кэш роутера отключён */
		if (CACHE_ROUTE_ENABLE === false)
		{
			return;
		}
		
		/* Получить фильтр по урлу */
		$in_filter = _Cache_Front::route_get_in_filter(G::url_path());
		if ($in_filter === null)
		{
			return;
		}
		
		/* Получить данные по маршруту */
		$data = _Cache_Front::route_get_data(G::url_path(), _Front::_in_serialize($in_filter));
		if ($data === null)
		{
			return;
		}
		
		/* Данные маршрута из кэша */
		return $data;
	}
}
?>