<?php
/**
 * Проводник
 */
class ZN_Explorer
{	
	/**
	 * Корневая папка
	 * 
	 * @var string
	 */
	public static $home;
	
	/**
	 * Урл корневой папки
	 * 
	 * @var string
	 */
	public static $home_url;
	
	/**
	 * Разрешённые папки
	 * 
	 * @var array
	 */
	public static $url_allow = [];
	
	/**
	 * Текст с последней ошибкой
	 * 
	 * @var string
	 */
	private static $_error;

	/**
	 * Проверить конфигурацию
	 */
	public static function config_check()
	{
		/* Корневая папка */
		if (empty(trim(ZN_EXPLORER_DIR_HOME)))
		{
			throw new Exception("Путь к корневой папке не задан.");
		}
		
		if (substr(ZN_EXPLORER_DIR_HOME_URL, 0, 1) !== "/")
		{
			throw new Exception("Путь к корневой папке задан неверно. Необходимо указать абсолютный путь.");
		}
		
		if (!is_dir(ZN_EXPLORER_DIR_HOME))
		{
			throw new Exception("Корневая папка «" . ZN_EXPLORER_DIR_HOME . "» задана неверно.");
		}
		
		self::$home = realpath(ZN_EXPLORER_DIR_HOME);
		
		/* Урл корневой папки */
		if (substr(ZN_EXPLORER_DIR_HOME_URL, 0, 1) !== "/")
		{
			throw new Exception("Урл корневой папки задан неверно. Необходимо указать абсолютный путь.");
		}
		
		self::$home_url = ZN_EXPLORER_DIR_HOME_URL;
		if (substr(self::$home_url, -1) === "/" and self::$home_url !== "/")
		{
			self::$home_url = substr(self::$home_url, 0, -1, "UTF-8");
		}
		
		/* Доступные папки */
		if (empty(ZN_EXPLORER_DIR_ALLOW))
		{
			self::$url_allow = [self::$home_url];
		}
		else
		{
			foreach (ZN_EXPLORER_DIR_ALLOW as $v)
			{
				if (substr($v, 0, 1) === "/")
				{
					throw new Exception("Разрешённая папка «{$v}» задана неверно. Необходимо указать относительный путь от корневой папки.");
				}
				
				if (!is_dir(self::$home . "/" . $v))
				{
					throw new Exception("Разрешённая папки «{$v}» не существует.");
				}
				
				
				if (self::$home_url !== "/")
				{
					self::$url_allow[] = self::$home_url . "/" . $v;
				}
				else
				{
					self::$url_allow[] = "/" . $v;
				}
				
			}
		}
	}
	
	/**
	 * Список файлов
	 * 
	 * @param string $url
	 * @param string $type
	 * @return array
	 */
	public static function ls($url = null, $type = "all")
	{
		/* Если урл не указан */
		if (empty($url))
		{
			$url = ZN_EXPLORER_DIR_HOME_URL;
			if (!empty(ZN_EXPLORER_DIR_ALLOW))
			{
				if (substr($url, -1) !== "/")
				{
					$url .= "/";
				}
				
				$url .= ZN_EXPLORER_DIR_ALLOW[0];
			}
		}
		
		/* Проверка */
		$parse_url = self::_url_parse($url);
		
		if (!in_array($type, ["all", "image"]))
		{
			throw new Exception("Тип задан неверно. Допустимые значения «all, image».");
		}
		
		/* Список файлов */
		$ls = [];
		$scandir = array_diff(scandir($parse_url['dir']), ["..", "."]);
		foreach ($scandir as $v)
		{
			$ftype = "file";
			if (is_dir($parse_url['dir'] . "/" . $v))
			{
				$ftype = "dir";
			}
			
			if (!self::_check($v, $ftype, $type))
			{
				continue;
			}
			
			if ($ftype === "dir")
			{
				$ls_dir[] = 
				[
					"name" => $v,
					"type" => "dir"
				];
			}
			elseif ($ftype === "file")
			{
				$ls_file[] = 
				[
					"name" => $v,
					"type" => "file"
				];
			}
			
		}
		
		/* Сначало папки, потом файлы */
		if (!empty($ls_dir))
		{
			$ls = array_merge($ls, $ls_dir);
		}
		if (!empty($ls_file))
		{
			$ls = array_merge($ls, $ls_file);
		}
		
		/* Возвращаем отпарсенный урл и файлы */
		return array_merge($parse_url, ["type" => $type, "ls" => $ls]);
	}
	
	/**
	 * Создать папку
	 * 
	 * @param string $url
	 * @param string $name
	 * @return array
	 */
	public static function mkdir($url, $name)
	{
		/* Проверка урл */
		$dir = self::_url_parse($url)['dir'];
		
		/* Проверка имени */
		if (!self::_check($name, "dir", "all"))
		{
			throw new Exception("Невозможно создать папку «" . $name . "». " . self::$_error);
		}
		
		/* Папка уже существует */
		if (is_dir($dir . "/" . $name))
		{
			throw new Exception("Папка «" . $name . "» уже существует.");
		}
		
		/* Создать папку */
		if (!@mkdir($dir . "/" . $name))
		{
			throw new Exception("Не удалось создать папку «" . $name . "». " . error_get_last()['message']);
		}
		
		return 
		[
			"dir" => $name
		];
	}
	
	/**
	 * Закачать файлы
	 * 
	 * @param string $url
	 * @param string $type
	 * @param string $path
	 * @param string $name
	 * @return array
	 */
	public static function upload($url, $type, $path, $name)
	{
		/* Проверка */
		$dir = self::_url_parse($url)['dir'];
		if (!in_array($type, ["all", "image"]))
		{
			throw new Exception("Тип задан неверно. Доступные значения «all, image».");
		}
		if (!is_file($path))
		{
			throw new Exception("Указанный файл отсутствует.");
		}
		
		/* Проверка имени */
		if (!self::_check($name, "file", $type))
		{
			throw new Exception("Невозможно закачать файл «" . $name . "». " . self::$_error);
		}
		
		/* Файл не должен превышать */
		if (filesize($path) > (ZN_EXPLORER_UPLOAD_FILE_SIZE_MAX * 1048576))
		{
			throw new Exception("Невозможно закачать файл «" . $name . "». Файл не должен превышать «" . ZN_EXPLORER_UPLOAD_FILE_SIZE_MAX . "» Мбайт." );
		}
		
		/* Закачать */
		if (!@copy($path, $dir . "/" . $name))
		{
			throw new Exception("Не удалось закачать файл «" . $name . "». " . error_get_last()['message']);
		}
		
		return
		[
			"name" => $name
		];
	}
	
	/**
	 * Переименовать
	 * 
	 * @param string $url
	 * @param string $type
	 * @param string $old
	 * @param string $new
	 * @return array
	 */
	public static function mv($url, $type, $old, $new)
	{
		/* Проверка */
		$dir = self::_url_parse($url)['dir'];
		if (!in_array($type, ["all", "image"]))
		{
			throw new Exception("Тип задан неверно. Доступные значения «all, image».");
		}
		if ($old === $new)
		{
			throw new Exception("Старое и новое имя идентичны.");
		}
		
		/* Файл или папка */
		if (is_file($dir . "/" . $old))
		{
			$ftype = "file";
		}
		elseif (is_dir($dir . "/" . $old))
		{
			$ftype = "dir";
		}
		else
		{
			throw new Exception("Файл или папка с именем «" . $old . "» отсутствует.");
		}
		
		/* Проверка старого имени */
		if (!self::_check($old, $ftype, $type))
		{
			throw new Exception("Невозможно переименовать «" . $old . "». " . self::$_error);
		}
		
		/* Проверка нового имени */
		if (!self::_check($new, $ftype, $type))
		{
			throw new Exception("Невозможно переименовать «" . $old . "» на «" . $new . "». " . self::$_error);
		}
		
		/* Переименовать */
		if (!@rename($dir . "/" . $old, $dir . "/" . $new))
		{
			throw new Exception("Не удалось переименовать «" . $old . "» на «" . $new . "». " . error_get_last()['message']);
		}
		
		return
		[
			"old" => $old,
			"new" => $new
		];
	}
	
	/**
	 * Удалить файл или папку
	 * 
	 * @param string $url
	 * @param string $type
	 * @param string $name
	 * @return array
	 */
	public static function rm($url, $type, $name)
	{
		/* Проверка */
		$dir = self::_url_parse($url)['dir'];
		if (!in_array($type, ["all", "image"]))
		{
			throw new Exception("Тип задан неверно. Доступные значения «all, image».");
		}
		
		/* Проверка */
		if (is_file($dir . "/" . $name))
		{
			$ftype = "file";
			if (!self::_check($name, $ftype, $type))
			{
				throw new Exception("Не удалось удалить файл «" . $name . "». " . self::$_error);
			}
		}
		elseif (is_dir($dir . "/" . $name))
		{
			$ftype = "dir";
			try
			{
				self::_check_dir($dir . "/" . $name, $type);
			}
			catch (Exception $e)
			{
				throw new Exception("Не удалось удалить папку «" . $name . "», т.к. в ней содержаться файлы или папки, которые вы не можете удалить. " . $e->getMessage());
			}
		}
		else
		{
			throw new Exception("Файл или папка «" . $name . "» отсутствует.");
		}
		
		/* Удалить */
		if ($ftype === "file")
		{
			if (!@unlink($dir . "/" . $name))
			{
				throw new Exception("Не удалось удалить файл «" . $name . "». " . error_get_last()['message']);
			}
		}
		elseif ($ftype === "dir")
		{
			self::_rm_dir($dir . "/" . $name, $type);
		}
		
		return
		[
			"name" => $name
		];
	}

	/**
	 * Проверить урл
	 * 
	 * @param string $url
	 */
	private static function _url_check($url)
	{
		/* Не указан */
		if (empty($url))
		{
			throw new Exception("Урл не указан.");
		}
		
		/* Недопустимые символы */
		if (mb_strpos($url, "..", 0, "UTF-8") !== false)
		{
			throw new Exception("Урл задан неверно. Недопустимые символы.");
		}
		
		/* Только абсолютный урл */
		if (substr($url, 0, 1) !== "/")
		{
			throw new Exception("Урл задан неверно. Необходимо указать абсолютный урл.");
		}
		
		/* Принадлежит ли к разрешённым папкам */
		$check = false;
		foreach (self::$url_allow as $v)
		{
			if (mb_strpos($url, $v . "/", 0, "UTF-8") !== false or $url === $v)
			{
				$check = true;
				break;
			}
		}
		
		if (!$check)
		{
			throw new Exception("К указанному урлу «{$url}» доступ запрещён.");
		}
	}

	/**
	 * Разобрать урл на папку и файл
	 * 
	 * @param string $url
	 * @return array
	 */
	public static function _url_parse($url)
	{
		/* Проверить урл */
		self::_url_check($url);
		if (substr($url, -1, 1) === "/")
		{
			$url = substr($url, 0, -1);
		}
		
		/* Получить путь к файлу */
		if (self::$home_url !== "/")
		{
			$path = self::$home . mb_substr($url, mb_strlen(self::$home_url));
		}
		else 
		{
			$path = self::$home . "/" . mb_substr($url, mb_strlen(self::$home_url));
		}
		
		/* Разобрать */
		if (is_file($path))
		{
			/* Урл папки */
			$url_explode = explode("/", $url);
			array_pop($url_explode);
			$url_dir = implode("/", $url_explode);
			
			$url_parse = 
			[
				"url" => $url,
				"url_dir" => $url_dir,
				"path" => realpath($path),
				"dir" => dirname($path),
				"file" => basename($path)
			];
			
		}
		elseif (is_dir($path))
		{
			$url_parse = 
			[
				"url" => $url,
				"url_dir" => $url,
				"path" => realpath($path),
				"dir" => realpath($path),
				"file" => null
			];
		}
		else
		{
			throw new Exception("Урл «" . $url  . " » задан неверно. Отсутствует указанный файл или каталог.");
		}
		
		/* Урл папки на уровень вверх */
		$url_dir_explode = explode("/", $url_parse['url_dir']);
		array_pop($url_dir_explode);
		$url_dir_top = implode("/", $url_dir_explode);
		
		$check = false;
		foreach (self::$url_allow as $v)
		{
			if (mb_strpos($url_dir_top, $v . "/", 0, "UTF-8") !== false or $url_dir_top === $v)
			{
				$check = true;
				break;
			}
		}
		
		if ($check)
		{
			$url_parse['url_dir_top'] = $url_dir_top;
		}
		else
		{
			$url_parse['url_dir_top'] = null;
		}
		
		return $url_parse;
	}
	
	/**
	 * Проверить
	 * 
	 * @param string $name
	 * @param string $ftype
	 * @param string $type
	 * @return boolean
	 */
	private static function _check(&$name, $ftype, $type)
	{
		try
		{
			/* Проверка */
			$name = trim($name);
			if (empty($name))
			{
				throw new Exception("Наименование не задано.");
			}
			
			/* Наличие недопустимых символов */
			if ($name === "." or $name === "..")
			{
				throw new Exception("Нельзя указывать имя как «.».");
			}
			if (mb_strpos($name, "..") !== false or mb_strpos($name, "/") !== false or mb_strpos($name, "~") !== false)
			{
				throw new Exception("Запрещены символы «..», «/», «~».");
			}
			
			/* Строгое именование файлов */
			if (ZN_EXPLORER_ASCII_ONLY === true and !self::_is_ascii($name))
			{
				throw new Exception("Недопустимые символы. Разрешено только a-z, «-», «_»");
			}
			
			/* Проверить папку */
			if ($ftype === "dir")
			{
				/* Не показывать скрытые папки */
				if (ZN_EXPLORER_DIR_HIDDEN_SHOW === false and self::_is_hidden($name))
				{
					throw new Exception("Папки начинающиеся на «.» запрещены.");
				}

			}
			/* Проверить файл */
			elseif ($ftype === "file")
			{
				/* Не показывать скрытые файлы */
				if (ZN_EXPLORER_FILE_HIDDEN_SHOW === false and self::_is_hidden($name))
				{
					throw new Exception("Файлы начинающиеся на «.» запрещены.");
				}

				/* Запрещённые расширения для файлов */
				if (!empty(ZN_EXPLORER_DENY_FILE_EXTENSION) and self::_is_deny($name))
				{
					throw new Exception("Запрещённое расширение.");
				}

				/* Только рисунки */
				if ($type === "image" and !self::_is_image($name))
				{
					throw new Exception("Не является рисунком.");
				}
			}
		}
		catch (Exception $e)
		{
			self::$_error = $e->getMessage();
			return false;
		}
		
		/* В нижний регистр */
		if (ZN_EXPLORER_LOWER_CASE === true)
		{
			$name = mb_strtolower($name, "UTF-8");
		}
		
		return true;
	}

	/**
	 * Проверить является ли файл запрещённый
	 * 
	 * @param string $file
	 * @return boolean
	 */
	private static function _is_deny($file)
	{
		/* Определить расширение */
		$explode = explode(".", $file);
		$ext = array_pop($explode);
		
		/* Проверка */
		if (in_array($ext, ZN_EXPLORER_DENY_FILE_EXTENSION))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Является ли файл рисуноком
	 * 
	 * @param string $file
	 * @return boolean
	 */
	private static function _is_image($file)
	{
		/* Определить расширение */
		$explode = explode(".", $file);
		$ext = array_pop($explode);

		/* Проверка */
		if (in_array($ext, ZN_EXPLORER_IMAGE_EXTENSION))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Содержит ли имя файла только ASCII-символы, а также «_», «.», «-»
	 * 
	 * @param string $file
	 * @return boolean
	 */
	private static function _is_ascii($file)
	{
		return ctype_alnum(str_replace(["_", ".", "-"], "", $file));
	}
	
	/**
	 * Является ли файл скрытным
	 * 
	 * @param string $file
	 * @return boolean
	 */
	private static function _is_hidden($file)
	{
		if (substr($file, 0, 1) === ".")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Проверка папки на наличие запрещённых файлов или каталогов.
	 * 
	 * @param string $dir
	 * @param string $type
	 * @return boolean
	 */
	private static function _check_dir($dir, $type)
	{
		$name = basename($dir);
		if (!self::_check($name, "dir", $type))
		{
			throw new Exception($name . " - " . self::$_error);
		}

		$scandir = array_diff(scandir($dir), ["..", "."]);
		foreach ($scandir as $v)
		{
			if (is_file($dir . "/" . $v))
			{
				if (!self::_check($v, "file", $type))
				{
					throw new Exception($v . " - " .self::$_error);
				}
			}
			elseif (is_dir($dir . "/" . $v))
			{
				self::_check_dir($dir . "/" . $v, $type);
			}
		}
	}

	/**
	 * Рекурсивоное удаление папки
	 * 
	 * @param string $dir
	 */
	private static function _rm_dir($dir)
	{
		$scandir = array_diff(scandir($dir), ["..", "."]);
		foreach ($scandir as $v)
		{
			if (is_file($dir . "/" . $v))
			{
				if (!@unlink($dir . "/" . $v))
				{
					throw new Exception("Не удалось удалить файл «" . $dir . "/" . $v . "». " . error_get_last()['message']);
				}
			}
			elseif (is_dir($dir . "/" . $v))
			{
				self::_rm_dir($dir . "/" . $v);
			}
		}
		
		if (!@rmdir($dir))
		{
			throw new Exception("Не удалось удалить папку «" . $dir . "». " . error_get_last()['message']);
		}
	}
}
?>