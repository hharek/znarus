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
	 * Подключенные куски html-а
	 * 
	 * @var array
	 */
	public static $_html_part = [];

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
		/* Поиск по источника */
		$redirect = _Seo_Redirect::get_by_from(G::url());
		if (empty($redirect))
		{
			return;
		}
		
		/* Сделать переадресацию на другую страницу */
		if ((bool)$redirect['Location'] === true)
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $redirect['To']);
			exit();
		}
		/* Урл сделать копией другого урла */
		elseif ((bool)$redirect['Location'] === false)
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
	}
	
	/**
	 * Общий CSS файл
	 */
	public static function index_css()
	{
		if (INDEX_CSS_ENABLE === true and parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === INDEX_CSS_URL)
		{
			/* Позволяем кэшировать */
			header_remove("Cache-Control");
			header_remove("Expires");
			header_remove("Pragma");
			
			/* Папки */
			$css_index = DIR_VAR . "/index.css";
			$css_dir = INDEX_CSS_DIR;
			
			/* Изменялись ли файлы css */
			$css_index_modify_time = 0;
			if (is_file($css_index))
			{
				$css_index_modify_time = filemtime($css_index);
			}

			$css = scandir($css_dir);
			$css = array_diff($css, ['..', '.']);

			$css_modify = false;
			foreach ($css as $val)
			{
				if (filemtime($css_dir . "/" . $val) > $css_index_modify_time)
				{
					$css_modify = true;
					break;
				}
			}
			
			/* Кэширование */
			if ($css_modify === false)
			{
				if 
				(
					!empty($_SERVER["HTTP_IF_MODIFIED_SINCE"]) and 
					strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) === $css_index_modify_time
				)
				{
					header("HTTP/1.1 304 Not Modified");
					header("Last-Modified: " . gmdate(LAST_MODIFIED_FORMAT_DATE, filemtime($css_index)));
					exit();
				}
				
				if 
				(
					!empty($_SERVER["HTTP_IF_NONE_MATCH"]) and
					$_SERVER["HTTP_IF_NONE_MATCH"] === "\"" . md5(SALT . file_get_contents($css_index)) . "\""
				)
				{
					header("HTTP/1.1 304 Not Modified");
					header("Last-Modified: " . gmdate(LAST_MODIFIED_FORMAT_DATE, filemtime($css_index)));
					exit();
				}
			}

			/* Сформировать css */
			if ($css_modify === true)
			{
				/* Создать общий css */
				$css_content = "";
				foreach ($css as $val)
				{
					$css_content .= file_get_contents($css_dir . "/" . $val) . "\n\n";
				}

				/* Сформировать */
				if (INDEX_CSS_LESS_ENABLE === true and !empty(trim($css_content)))
				{
					$parser = new Less_Parser();
					$parser->parse($css_content);
					$css_content = $parser->getCss();
				}

				file_put_contents($css_index, $css_content);
			}

			/* Вывод */
			$css_content = file_get_contents($css_index);

			header("Last-Modified: " . gmdate(LAST_MODIFIED_FORMAT_DATE, filemtime($css_index)));
			header("Content-Type: text/css; charset=utf-8");
			header("Etag: \"" . md5(SALT . $css_content) . "\"");
			
			/* Заголовки gzip */
			if (INDEX_CSS_GZIP_ENABLE === true and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip") !== false)
			{
				ini_set("zlib.output_compression", "0");
				header("Content-Encoding: gzip");
				$css_content = gzencode($css_content);
			}

			echo $css_content;

			exit();
			
		}
	}
	
	/**
	 * Общий JS файл
	 */
	public static function index_js()
	{
		if (INDEX_JS_ENABLE === true and parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === INDEX_JS_URL)
		{
			/* Позволяем кэшировать */
			header_remove("Cache-Control");
			header_remove("Expires");
			header_remove("Pragma");
			
			/* Папки */
			$js_index = DIR_VAR . "/index.js";
			$js_dir = INDEX_JS_DIR;
			
			/* Изменялись ли файлы js */
			$js_index_modify_time = 0;
			if (is_file($js_index))
			{
				$js_index_modify_time = filemtime($js_index);
			}

			$js = scandir($js_dir);
			$js = array_diff($js, ['..', '.']);

			$js_modify = false;
			foreach ($js as $val)
			{
				if (filemtime($js_dir . "/" . $val) > $js_index_modify_time)
				{
					$js_modify = true;
					break;
				}
			}
			
			/* Кэширование */
			if ($js_modify === false)
			{
				if 
				(
					!empty($_SERVER["HTTP_IF_MODIFIED_SINCE"]) and 
					strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) === $js_index_modify_time
				)
				{
					header("HTTP/1.1 304 Not Modified");
					header("Last-Modified: " . gmdate(LAST_MODIFIED_FORMAT_DATE, filemtime($js_index)));
					exit();
				}
				
				if 
				(
					!empty($_SERVER["HTTP_IF_NONE_MATCH"]) and
					$_SERVER["HTTP_IF_NONE_MATCH"] === "\"" . md5(SALT . file_get_contents($js_index)) . "\""
				)
				{
					header("HTTP/1.1 304 Not Modified");
					header("Last-Modified: " . gmdate(LAST_MODIFIED_FORMAT_DATE, filemtime($js_index)));
					exit();
				}
			}

			/* Сформировать js */
			if ($js_modify === true)
			{
				/* Создать общий js */
				$js_content = "";
				foreach ($js as $val)
				{
					$js_content .= file_get_contents($js_dir . "/" . $val) . "\n\n";
				}

				file_put_contents($js_index, $js_content);
			}

			/* Вывод */
			$js_content = file_get_contents($js_index);

			header("Last-Modified: " . gmdate(LAST_MODIFIED_FORMAT_DATE, filemtime($js_index)));
			header("Content-Type: application/javascript; charset=utf-8");
			header("Etag: \"" . md5(SALT . $js_content) . "\"");
			
			/* Заголовки gzip */
			if (INDEX_JS_GZIP_ENABLE === true and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip") !== false)
			{
				ini_set("zlib.output_compression", "0");
				header("Content-Encoding: gzip");
				$js_content = gzencode($js_content);
			}

			echo $js_content;

			exit();
			
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
			if (Type::check("url_part", $url_part) === false)
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
			$data = self::_data();
			
			_Cache_Front::page_set(G::url_path(), self::$_exe_in_filter, $in_serialize, $data);
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
				if (!Type::check("identified", $_session_id))
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
//					$_session['IP'] !== md5($_session['ID'] . SALT . $_SERVER['REMOTE_ADDR']) or 
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
				"html_part" => self::$_html_part,
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
			self::$_html_part = $data['html_part'];
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