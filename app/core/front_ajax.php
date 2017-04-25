<?php
/**
 * Вывод. Аякс
 */
class _Front_Ajax
{
	/**
	 * Идентификатор модуля
	 * 
	 * @var string
	 */
	public static $_module;
	
	/**
	 * Тип модуля
	 * 
	 * @var string
	 */
	public static $_module_type;
	
	/**
	 * Идентификатор аякса
	 * 
	 * @var string
	 */
	public static $_ajax;
	
	/**
	 * Тип возвращаемых данных (json,html,text,json_page)
	 * 
	 * @var string 
	 */
	public static $_data_type;
	
	/**
	 * Обработка GET данных
	 * 
	 * @var boolean
	 */
	public static $_get;
	
	/**
	 * Обработка POST данных
	 * 
	 * @var boolean 
	 */
	public static $_post;
	
	/**
	 * Использовать кэширование
	 * 
	 * @var boolean
	 */
	public static $_cache;
	
	/**
	 * Фильтр входящих данных
	 * 
	 * @var array 
	 */
	public static $_in_filter = [];
	
	/**
	 * Используется кэш аякса
	 * 
	 * @var boolean
	 */
	public static $_cache_use = false;

	/**
	 * Возращаемое значение при выполении Data_Type = json
	 * 
	 * @var mixed
	 */
	public static $json_return;

	/**
	 * Заголовок
	 * 
	 * @var string 
	 */
	public static $title;
	
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
	public static $content;
	
	/**
	 * Тэги страницы
	 * 
	 * @var string
	 */
	public static $tags;
	
	/**
	 * Время последнего изменения страницы
	 * 
	 * @var string 
	 */
	public static $last_modified;
	
	/**
	 * Тэг Etag - уникальный идентификатор кэша для браузера
	 * 
	 * @var string
	 */
	public static $etag;
	
	/**
	 * Сообщение об удачном выполнении
	 * 
	 * @var string 
	 */
	public static $mess_ok;
	
	/**
	 * Переход на другую страницу
	 * 
	 * @var string
	 */
	public static $redirect;
	
	/**
	 * CSS-код
	 * 
	 * @var string
	 */
	public static $css;

	/**
	 * Javascript-код заданный через функцию js()
	 * 
	 * @var string
	 */
	public static $js;
	
	/**
	 * Javascript-код находящийся в файле
	 * 
	 * @var string
	 */
	public static $js_file;
	
	/**
	 * Ошибки при заполении формы
	 * 
	 * @var array
	 */
	public static $error_form;
	
	/**
	 * Текст ошибки
	 * 
	 * @var string
	 */
	public static $error;
	
	/**
	 * Содержимое аякса
	 * 
	 * @var string
	 */
	public static $output;

	/**
	 * Проверка и получение сведения по аяксу
	 */
	public static function check()
	{
		try
		{
			/* Проверка урла */
			if (count(G::url_path_ar()) !== 3)
			{
				throw new Exception("Урл задан неверно.");
			}

			if (!Type::check("identified", G::url_path_ar()[1]) or ! Type::check("identified", G::url_path_ar()[2]))
			{
				throw new Exception("Урл задан неверно.");
			}
		}
		catch (Exception $e)
		{
			header("HTTP/1.1 404 Not Found");
			header("Content-type: text/plain");
			echo $e->getMessage();
			exit();
		}
	}
	
	/**
	 * Получить кэш аякса
	 */
	public static function cache_get()
	{
		/* Не искать если кэш роутера отключён */
		if (CACHE_AJAX_ENABLE === false)
		{
			return;
		}
		
		/* Получить фильтр */
		$in_filter = _Cache_Front::ajax_get_in_filter(G::url_path_ar()[1], G::url_path_ar()[2]);
		if ($in_filter === null)
		{
			return;
		}
		
		/* Получить данные */
		$data = _Cache_Front::ajax_get_data(G::url_path_ar()[1], G::url_path_ar()[2], self::_in_serialize($in_filter));
		if ($data === null)
		{
			return;
		}
		
		/* Назначить данные аяксу */
		self::_data($data);
		self::$_cache_use = true;
	}

	/**
	 * Данные по аяксу
	 */
	public static function data()
	{
		try
		{
			/* Сведения по аяксу */
			$ajax = G::cache_db()->get("ajax_by_identified_" . G::url_path_ar()[1] . "_" . G::url_path_ar()[2]);
			if ($ajax === null)
			{
				$ajax = G::db_core()->ajax_by_identified(G::url_path_ar()[1], G::url_path_ar()[2])->row();
				G::cache_db()->set("ajax_by_identified_" . G::url_path_ar()[1] . "_" . G::url_path_ar()[2], $ajax, "ajax");
			}

			/* Отсутствует */
			if (empty($ajax))
			{
				throw new Exception("Указанный аякс отсутствует");
			}

			/* Назначить данные */
			self::$_module = $ajax['Module_Identified'];
			self::$_module_type = _Module::get_type_by_identified(self::$_module);
			self::$_ajax = $ajax['Identified'];
			self::$_data_type = $ajax['Data_Type'];
			if (self::$_data_type === "json_page")
			{
				self::$_get = (bool)$ajax['Get'];
				self::$_post = (bool)$ajax['Post'];
			}
			self::$_cache = (bool)$ajax['Cache'];

			/* Проверка HTTP запроса для json_page */
			if (self::$_data_type === "json_page")
			{
				if ($_SERVER['REQUEST_METHOD'] === "GET" and self::$_get === false)
				{
					throw new Exception("Обработка HTTP-метода GET отсутствует.");
				}

				if ($_SERVER['REQUEST_METHOD'] === "POST" and self::$_post === false)
				{
					throw new Exception("Обработка HTTP-метода POST отсутствует.");
				}
			}
		}
		catch (Exception $e)
		{
			header("HTTP/1.1 404 Not Found");
			header("Content-type: text/plain");
			echo $e->getMessage();
			exit();
		}
	}

	/**
	 * Загрузка файла
	 */
	public static function load()
	{
		switch (self::$_data_type)
		{
			case "json":
			{
				self::$json_return = call_user_func(function()
				{
					return require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json/" . self::$_ajax . ".php";
				});
				
				self::$output = json_encode(self::$json_return, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			}
			break;
		
			case "html":
			{
				ob_start();
				call_user_func(function ()
				{
					/* PHP и HTML */
					require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/html/act/" . self::$_ajax . ".php";
					require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/html/html/" . self::$_ajax . ".html";

					/* CSS */
					if(is_file(DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/html/css/" . self::$_ajax . ".css"))
					{
						echo "\n<style>\n";
						require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/html/css/" . self::$_ajax . ".css";
						echo "\n</style>\n";
					}

					/* Javascript */
					if(is_file(DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/html/js/" . self::$_ajax . ".js"))
					{
						echo "\n<script>\n";
						require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/html/js/" . self::$_ajax . ".js";
						echo "\n</script>\n";
					}
				});
				self::$output = ob_get_contents();
				ob_end_clean();
			}
			break;
		
			case "text":
			{
				ob_start();
				call_user_func(function ()
				{
					require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/text/" . self::$_ajax . ".php";
				});
				self::$output = ob_get_contents();
				ob_end_clean();
			}
			break;
		
			case "json_page":
			{
				self::_json_page_load();
				self::_json_page_output();
			}
			break;
		}
	}
	
	/**
	 * Создать кэш аякса
	 */
	public static function cache_set()
	{
		if (CACHE_AJAX_ENABLE === true and self::$_cache === true)
		{
			$in_serialize = self::_in_serialize(self::$_in_filter);
			
			/* Создать тэг Etag */
			self::$etag = md5(self::$_module . "_" . self::$_ajax . "_" . $in_serialize . SALT .  self::$output);
			
			/* Создать кэш аякса */
			$data = self::_data();
			_Cache_Front::ajax_set(self::$_module, self::$_ajax, self::$_in_filter, $in_serialize, $data);
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
				if (!empty(self::$last_modified))
				{
					header("Last-Modified: " . self::$last_modified);
				}
				exit();
			}
		}
	}

	/**
	 * Заголовки
	 */
	public static function header()
	{
		/* Общие заголовки */
		switch (self::$_data_type)
		{
			case "json":
			{
				header("Content-type: application/json; charset=UTF-8");
			}
			break;
		
			case "html":
			{
				header("Content-type: text/html; charset=UTF-8");
			}
			break;
		
			case "text":
			{
				header("Content-type: text/plain; charset=UTF-8");
			}
			break;
		
			case "json_page":
			{
				header("Content-type: application/json; charset=UTF-8");
			}
			break;
		}
		
		/* Заголовок «Last-Modified» */
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
	 * Загрузка json_page 
	 */
	private static function _json_page_load()
	{
		try
		{
			/* Загрузка файла */
			ob_start();
			call_user_func(function ()
			{
				if($_SERVER['REQUEST_METHOD'] === "GET")
				{
					require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/get/" . self::$_ajax . ".php";
					if (is_file(DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/html/" . self::$_ajax . ".html"))
					{
						require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/html/" . self::$_ajax . ".html";
					}
				}
				elseif ($_SERVER['REQUEST_METHOD'] === "POST")
				{
					require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/post/" . self::$_ajax . ".php";
				}
			});
			self::$content = ob_get_contents();
			ob_end_clean();
			
			/* CSS */
			if
			(
				$_SERVER['REQUEST_METHOD'] === "GET" and 
				is_file(DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/css/" . self::$_ajax . ".css")
			)
			{
				ob_start();
				require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/css/" . self::$_ajax . ".css";
				self::$css = ob_get_contents();
				ob_end_clean();
			}

			/* Javascript в файле */
			if
			(
				$_SERVER['REQUEST_METHOD'] === "GET" and
				is_file(DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/js/" . self::$_ajax . ".js")
			)
			{
				ob_start();
				require DIR_APP . "/" . self::$_module_type . "/" . self::$_module . "/ajax/json_page/js/" . self::$_ajax . ".js";
				self::$js_file = ob_get_contents();
				ob_end_clean();
			}
		}
		/* Ошибка в форме */
		catch (Exception_Form $e)
		{
			self::$error_form = Err::get();
		}
		/* Ошибка */
		catch (Exception $e)
		{
			self::$error = $e->getMessage();
		}
	}
	
	/**
	 * Выдать JSON для json_page
	 */
	private static function _json_page_output()
	{
		$data = [];
		
		if (!empty(self::$title))
		{
			$data['title'] = self::$title;
		}
		
		if (!empty(self::$path))
		{
			$data['path'] = self::$path;
		}
		
		if (!empty(self::$content))
		{
			$data['content'] = self::$content;
		}
		
		if (!empty(self::$tags))
		{
			$data['tags'] = self::$tags;
		}
		
		if (!empty(self::$css))
		{
			$data['css'] = self::$css;
		}
		
		if (!empty(self::$js))
		{
			$data['js'] = self::$js;
		}
		
		if (!empty(self::$js_file))
		{
			$data['js_file'] = self::$js_file;
		}
		
		if (!empty(self::$mess_ok))
		{
			$data['mess_ok'] = self::$mess_ok;
		}
		
		if (!empty(self::$redirect))
		{
			$data['redirect'] = self::$redirect;
		}
		
		if (!empty(self::$error_form))
		{
			$data['error_form'] = self::$error_form;
		}
		
		if (!empty(self::$error))
		{
			$data['error'] = self::$error;
		}
		
		self::$output = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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
				"module" => self::$_module,
				"module_type" => self::$_module_type,
				"ajax" => self::$_ajax,
				"data_type" => self::$_data_type,
				"get" => self::$_get,
				"post" => self::$_post,
				"cache" => self::$_cache,
				"in_filter" => self::$_in_filter,
				"json_return" => self::$json_return,
				"title" => self::$title,
				"path" => self::$path,
				"content" => self::$content,
				"tags" => self::$tags,
				"last_modified" => self::$last_modified,
				"etag" => self::$etag,
				"mess_ok" => self::$mess_ok,
				"redirect" => self::$redirect,
				"css" => self::$css,
				"js" => self::$js,
				"js_file" => self::$js_file,
				"error_form" => self::$error_form,
				"error" => self::$error,
				"output" => self::$output
			];
		}
		else
		{
			self::$_module = $data['module'];
			self::$_module_type = $data['module_type'];
			self::$_ajax = $data['ajax'];
			self::$_data_type = $data['data_type'];
			self::$_get = $data['get'];
			self::$_post = $data['post'];
			self::$_cache = $data['cache'];
			self::$_in_filter = $data['in_filter'];
			self::$json_return = $data['json_return'];
			self::$title = $data['title'];
			self::$path = $data['path'];
			self::$content = $data['content'];
			self::$tags = $data['tags'];
			self::$last_modified = $data['last_modified'];
			self::$etag = $data['etag'];
			self::$mess_ok = $data['mess_ok'];
			self::$redirect = $data['redirect'];
			self::$css = $data['css'];
			self::$js = $data['js'];
			self::$js_file = $data['js'];
			self::$error_form = $data['error_form'];
			self::$error = $data['error'];
			self::$output = $data['output'];
		}
	}
}

?>