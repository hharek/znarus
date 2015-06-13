<?php
/**
 * Основной вывод
 */
class _Front
{
	/**
	 * Урл корректный
	 * 
	 * @var boolean
	 */
	public static $_url_correct = true;
	
	/**
	 * Сведения по модулю исполнителя
	 * 
	 * @var array
	 */
	public static $_module_exe = [];
	
	/**
	 * Сведения по исполнителю
	 * 
	 * @var array
	 */
	public static $_exe = [];
	
	/**
	 * Запускаемые инки
	 * 
	 * @var array
	 */
	public static $_inc = [];

	/**
	 * Тип страницы (home, 404, 403, module)
	 * 
	 * @var string
	 */
	public static $_page_type;
	
	/**
	 * Доступ к странице разрещён
	 * 
	 * @var boolean
	 */
	public static $_access = true;
	
	/**
	 * Данные по шаблону
	 * 
	 * @var array
	 */
	public static $_html = [];
	
	/**
	 * Фильтр входящих переменных маршрута
	 * 
	 * @var type 
	 */
	public static $_route_in_filter = [];
	
	/**
	 * Фильтр входящих переменных страницы
	 * 
	 * @var type 
	 */
	public static $_exe_in_filter = [];

	/**
	 * Маршрут взят из кэша
	 * 
	 * @var boolean
	 */
	public static $_cache_route = false;
	
	/**
	 * Страница взята из кэша
	 * 
	 * @var boolean
	 */
	public static $_cache_page = false;

	/**
	 * Заголовок страницы
	 * 
	 * @var string
	 */
	public static $title = "";
	
	/**
	 * Путь страницы (хлебная крошка)
	 * 
	 * @var array
	 */
	public static $path = [];
	
	/**
	 * Активное содержимое страницы
	 * 
	 * @var string
	 */
	public static $content = "";
	
	/**
	 * Тэги страницы
	 * 
	 * @var string
	 */
	public static $tags = "";
	
	/**
	 * Время последнего изменения страницы
	 * 
	 * @var string 
	 */
	public static $last_modified = "";
	
	/**
	 * Тэг Etag - уникальный идентификатор кэша для браузера
	 * 
	 * @var string
	 */
	public static $etag = "";

	/**
	 * Тэг title
	 * 
	 * @var string
	 */
	public static $meta_title = "";
	
	/**
	 * Тэг meta name="keywords"
	 * 
	 * @var string
	 */
	public static $meta_keywords = "";
	
	/**
	 * Тэг meta name="description"
	 * 
	 * @var string 
	 */
	public static $meta_description = "";
	
	/**
	 * Содержимое страницы
	 * 
	 * @var string
	 */
	public static $output = "";

	/**
	 * Переадресация на другую страницу
	 */
	public static function redirect()
	{
		$redirect_all = _Seo_Redirect::get_all();
		
		$url = G::url();
		foreach ($redirect_all as $redirect)
		{
			if ($redirect['From'] === $url)
			{
				$location = (bool)$redirect['Location'];

				/* Сделать редирект на другую страницу */
				if ($location === true)
				{
					$protocol = "http";
					if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === "on")
					{
						$protocol = "https";
					}

					header("HTTP/1.1 301 Moved Permanently");
					header("Location: " . $protocol . "://". $_SERVER['HTTP_HOST'] . $redirect['To']);
					exit();
				}
				/* Урл сделать копией другого урла */
				elseif ($location === false)
				{
					G::url($redirect['To']);
					G::url_path(parse_url(G::url(), PHP_URL_PATH));
					if (G::url_path() !== "/")
					{
						G::url_path_ar(explode("/", mb_substr(G::url_path(), 1)));
					}
					else
					{
						G::url_path_ar([]);
					}
				}
				
				break;
			}
		}
	}
	
	/**
	 * Проверяем и форматируем урл
	 */
	public static function url_check()
	{
		if (URL_END !== "" and G::url_path() !== "/")
		{
			/* Если URL_PATH заканчивается не на «окончание урла (URL_END)» то 404 */
			if (mb_substr(G::url_path(), 0 - mb_strlen(URL_END)) !== URL_END)
			{
				self::$_url_correct = false;
				return;
			}

			/* Новые глобальные переменные с учётом «окончания урла» */
			G::url_path(mb_substr(G::url_path(), 0, mb_strlen(G::url_path()) - mb_strlen(URL_END)));
			G::url_path_ar(explode("/", mb_substr(G::url_path(), 1)));
		}
		
		/* Недопустимые символы в URL_PATH */
		$url_path = G::url_path_ar();
		foreach ($url_path as $url_part)
		{
			if (Chf::url_part($url_part) === false)
			{
				self::$_url_correct = false;
				return;
			}
		}
	}
	
	/**
	 * Определение маршрута
	 */
	public static function route()
	{	
		/* Получаем маршрут */
		$route_data = _Route::get();
		if (!empty($route_data['cache_route']))
		{
			self::$_cache_route = true;
		}
		
		/* Если использовалься кэш роутера, выставляем глобальные переменные, определённые в route.php */
		if (!empty($route_data['global']))
		{
			foreach ($route_data['global'] as $key => $val)
			{
				G::_set($key, $val);
			}
		}
		
		/* Записываем данные исполнителя и его модуля */
		self::$_page_type = $route_data['page_type'];
		self::$_module_exe = _Module::get_by_identified($route_data['module']);
		self::$_module_exe['Type'] = _Module::get_type_by_identified($route_data['module']);
		self::$_exe = _Exe::get_by_identified($route_data['module'], $route_data['exe']);
	}
	
	/**
	 * Получить кэш страницы
	 */
	public static function cache_page_get()
	{
		if (CACHE_PAGE_ENABLE === true and (bool)self::$_exe['Cache_Page'] === true)
		{
			/* Получить кэш стандартной страницы */
			if (self::$_page_type === "module")
			{
				$in_filter = _Cache_Front::page_get_in_filter(self::$_module_exe['Identified'], self::$_exe['Identified']);
				if ($in_filter === null)
				{
					return;
				}
				$data = _Cache_Front::page_get_data(G::url_path(), _Front::_in_serialize($in_filter));
				if ($data === null)
				{
					return;
				}
			}
			/* Получить кэш другой страницы */
			elseif (self::$_page_type === "home" or self::$_page_type === "404" or self::$_page_type === "403")
			{
				$data = _Cache_Front::page_get_data_other(self::$_page_type);
				if ($data === null)
				{
					return;
				}
			}
			
			/* Назначить данные странице */
			self::_data($data);
			self::$_cache_page = true;
		}
	}
	
	/**
	 * Процедуры
	 */
	public static function proc()
	{
		$proc_all = _Proc::get_all(true);
		
		foreach ($proc_all as $proc)
		{
			call_user_func(function ($_proc)
			{
				require DIR_APP . "/" . _Module::get_type_by_identified($_proc['Module_Identified']) . "/" . $_proc['Module_Identified'] . "/proc/" . $_proc['Identified'] . ".php";
			}, $proc);
		}
	}
	
	/**
	 * Разрешён ли доступ к странице
	 */
	public static function access()
	{
		/* Проверять на доступ только page_type = module */
		if (self::$_page_type !== "module")
		{
			return;
		}
		
		/* Модули влияющий на доступ глобально */
		$module_access_global = _Module::get_by_type("all", 1, "global");
		foreach ($module_access_global as $module)
		{
			if (!is_file(DIR_APP . "/" . $module['Type'] . "/" . $module['Identified'] . "/access.php"))
			{
				continue;
			}

			$_return = call_user_func(function($_module)
			{
				return require DIR_APP . "/" . $_module['Type'] . "/" . $_module['Identified'] . "/access.php";

			}, $module);

			if ($_return === false)
			{
				self::$_access = false;
				break;
			}
		}
		
		/* Если текущий модуль влияет на доступ локально */
		if 
		(
			self::$_access === true and 
			self::$_module_exe['Access'] === "local" and
			is_file(DIR_APP . "/" . self::$_module_exe['Type'] . "/" . self::$_module_exe['Identified'] . "/access.php")
		)
		{
			$return = call_user_func(function($_module)
			{
				return require DIR_APP . "/" . $_module['Type'] . "/" . $_module['Identified'] . "/access.php";

			}, self::$_module_exe);

			if ($return === false)
			{
				self::$_access = false;
			}
		}
		
		/* Если доступ запрещён меняем исполнителя */
		if (self::$_access === false)
		{
			$route_data = self::_route_std("403");
			
			self::$_page_type = $route_data['page_type'];
			self::$_module_exe = _Module::get_by_identified($route_data['module']);
			self::$_module_exe['Type'] = _Module::get_type_by_identified($route_data['module']);
			self::$_exe = _Exe::get_by_identified($route_data['module'], $route_data['exe']);
		}
	}
	
	/**
	 * Выбор шаблона
	 */
	public static function html_set()
	{
		/* Шаблон по умолчанию */
		$html_identified = P::get("html_default");
		
		/* Запускаем html_set.php */
		if (is_file(DIR_APP . "/" . self::$_module_exe['Type'] . "/" . self::$_module_exe['Identified'] . "/html_set.php"))
		{
			$return = call_user_func(function($_module)
			{
				return require DIR_APP . "/" . $_module['Type'] . "/" . $_module['Identified'] . "/html_set.php";
			}, self::$_module_exe);
			
			if (is_string($return) and !empty($return))
			{
				$html_identified = $return;
			}
		}
		
		/* Данные по шаблону */
		self::$_html = _Html::get_by_identified($html_identified);
	}
	
	/**
	 * Исполнитель
	 */
	public static function exe()
	{
		ob_start();
		call_user_func(function ($_module, $_exe)
		{
			require DIR_APP . "/" . $_module['Type'] . "/" . $_module['Identified'] . "/exe/act/" . $_exe['Identified'] . ".php";
			require DIR_APP . "/" . $_module['Type'] . "/" . $_module['Identified'] . "/exe/html/" . $_exe['Identified'] . ".html";
		}, self::$_module_exe, self::$_exe);
		self::$content = ob_get_contents();
		ob_end_clean();
		
	}
	
	/**
	 * Загрузка шаблона
	 */
	public static function html()
	{
		ob_start();
		call_user_func(function ()
		{
			require DIR_APP . "/html/" . self::$_html['Identified'] . ".html";
		});
		self::$output = ob_get_contents();
		ob_end_clean();
	}
	
	/**
	 * Создать кэш страницы
	 */
	public static function cache_page_set()
	{
		if (CACHE_PAGE_ENABLE === true and (bool)self::$_exe['Cache_Page'] === true)
		{
			$in_serialize = self::_in_serialize(self::$_exe_in_filter);
			
			/* Создать тэг Etag */
			self::$etag = md5(G::url_path() . $in_serialize . SALT .  self::$output);
			
			/* Создать кэш страницы */
			_Cache_Front::page_set(G::url_path(), self::$_exe_in_filter, $in_serialize, self::_data());
		}
	}

	/**
	 * Проверить изменилась ли страница
	 */
	public static function is_modified()
	{
		/* В заголовке запроса присутствует «If-Modified-Since» */
		if (!empty($_SERVER["HTTP_IF_MODIFIED_SINCE"]) and !empty(self::$last_modified))
		{
			if (strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) >= strtotime(self::$last_modified))
			{
				header("HTTP/1.1 304 Not Modified");
				header("Last-Modified: " . self::$last_modified);
				exit();
			}
		}
		
		/* В заголовке запроса присутствует «If-None-Match» */
		if (!empty($_SERVER["HTTP_IF_NONE_MATCH"]) and !empty(self::$etag))
		{
			$if_none_match = str_replace("\"", "", $_SERVER["HTTP_IF_NONE_MATCH"]);
			if ($if_none_match === self::$etag)
			{
				header("HTTP/1.1 304 Not Modified");
				header("Last-Modified: " . self::$last_modified);
				exit();
			}
		}
	}
	
	/**
	 * Данные по странице
	 */
	public static function data()
	{
		if (isset($_GET[FRONT_INFO_GET]))
		{
			try
			{
				/* Проверка номера сессии */
				$_session_id = $_GET[FRONT_INFO_GET];
				if (!Chf::identified($_session_id))
				{
					throw new Exception("Номер сессии указан неверно.");
				}

				/* Проверка сессии */
				$query = 
<<<SQL
SELECT 
	"ID",
	"Date",
	"IP",
	"Browser",
	COALESCE ("User_ID", 0) as "User_ID"
FROM 
	"user_session"
WHERE 
	"ID" = $1 
SQL;
				$_session = G::db_core()->query($query, $_session_id)->row();
				if (empty($_session))
				{
					throw new Exception("Сессии с указанным номером не существует.");
				}

				if 
				(
					strtotime($_session['Date']) + ADMIN_SESSION_TIME < time() or
					$_session['IP'] !== md5($_session['ID'] . SALT . $_SERVER['REMOTE_ADDR']) or 
					$_session['Browser'] !== md5($_session['ID'] . SALT . $_SERVER['HTTP_USER_AGENT'])
				)
				{
					throw new Exception("Данные сессии не совпадают с вашими данными.");
				}

				/* Выдать все сведения по странице в json-формате */
				header("Content-type: application/json; charset=UTF-8");
				echo json_encode(self::_data(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
				exit();
			}
			catch (Exception $e)
			{
				$e->getMessage();
			}
		}
	}
	
	/**
	 * Заголовки
	 */
	public static function header()
	{
		/* Общие заголовоки */
		switch (self::$_page_type)
		{
			case "404":
			{
				header("HTTP/1.1 404 Not Found");
			}
			break;
		
			case "403":
			{
				header("HTTP/1.1 403 Forbidden");
			}
			break;
		}
		
		/* Дата последнего изменения страницы */
		if (!empty(self::$last_modified))
		{
			header("Last-Modified: " . self::$last_modified);
		}
		
		/* Тэг Etag */
		if (!empty(self::$etag))
		{
			header("Etag: \"" . self::$etag . "\"");
		}
	}

	/**
	 * Вывод
	 */
	public static function output()
	{
		echo self::$output;
	}
	
	/**
	 * Получить сериализованное представление входящих переменных, фильтруя «суперглобальные» переменные
	 * 
	 * @param array $in_filter
	 * @return string
	 */
	public static function _in_serialize($in_filter)
	{
		$hash_ar = [];

		foreach ($in_filter as $type => $param)
		{
			/* Если указан тип неизвестен */
			if (!in_array($type, ['get','post','cookie','session']))
			{
				continue;
			}
			
			/* Если null ничего не делаем */
			if ($param === null)
			{
				continue;
			}

			/* Ссылка на «суперглобальную» переменную */
			switch ($type)
			{
				case "get"		: $global = $_GET;		break;
				case "post"		: $global = $_POST;		break;
				case "cookie"	: $global = $_COOKIE;	break;
				case "session"	: $global = $_SESSION;	break;
			}

			/* Если указан пустой массив, то возвращается вся «суперглобальная» переменная */
			if (is_array($param) and count($param) === 0)
			{
				$global_main = $global;
			}
			/* Если массив не пустой, то возвращаем только указанные ключи из «суперглобальной» переменной */
			elseif (is_array($param) and count($param) > 0)
			{
				$global_main = [];
				foreach ($global as $key => $val)
				{
					if (in_array($key, $param))
					{
						$global_main[$key] = $val;
					}
				}
			}
			/* Если тип POST и указано значение «isset» */
			elseif ($type === "post" and $param === "isset")
			{
				if ($_SERVER['REQUEST_METHOD'] === "GET")
				{
					$global_main = false;
				}
				elseif ($_SERVER['REQUEST_METHOD'] === "POST")
				{
					$global_main = true;
				}
			}

			/* Сортируем по ключам */
			if (!empty($global_main) and !is_bool($global_main))
			{
				ksort($global_main);
			}

			/* Добавляем к хэшу сериализованную «суперглобальную» переменную */
			$hash_ar[$type] = $global_main;
		}

		return serialize($hash_ar);
	}
	
	/**
	 * Получить или назначить данные по странице
	 * 
	 * @return array
	 */
	private static function _data(&$data = null)
	{
		if ($data === null)
		{
			return
			[
				"url_correct" => self::$_url_correct,
				"module_exe" => self::$_module_exe,
				"exe" => self::$_exe,
				"inc" => self::$_inc,
				"page_type" => self::$_page_type,
				"access" => self::$_access,
				"html" => self::$_html,
				"title" => self::$title,
				"path" => self::$path,
				"content" => self::$content,
				"tags" => self::$tags,
				"last_modified" => self::$last_modified,
				"etag" => self::$etag,
				"meta_title" => self::$meta_title,
				"meta_keywords" => self::$meta_keywords,
				"meta_description" => self::$meta_description,
				"output" => self::$output
			];
		}
		else
		{
			self::$_url_correct = $data['url_correct'];
			self::$_module_exe = $data['module_exe'];
			self::$_exe = $data['exe'];
			self::$_inc = $data['inc'];
			self::$_page_type = $data['page_type'];
			self::$_access = $data['access'];
			self::$_html = $data['html'];
			self::$title = $data['title'];
			self::$path = $data['path'];
			self::$content = $data['content'];
			self::$tags = $data['tags'];
			self::$last_modified = $data['last_modified'];
			self::$etag = $data['etag'];
			self::$meta_title = $data['meta_title'];
			self::$meta_keywords = $data['meta_keywords'];
			self::$meta_description = $data['meta_description'];
			self::$output = $data['output'];
		}
	}
}
?>