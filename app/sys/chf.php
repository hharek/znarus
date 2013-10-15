<?php
/**
 * Checker Field - проверщик полей формы
 */
class Chf
{

	/**
	 * Текст последней ошибки
	 * 
	 * @var string
	 */
	private static $error = "";
	
	/**
	 * Максимальный размер строки в байтах (1 Мб)
	 * 
	 * @var type 
	 */
	private static $max_size = 1048576;

	/**
	 * Последняя ошибка
	 * 
	 * @return string
	 */
	public static function error()
	{
		return self::$error;
	}

	/**
	 * Функции
	 * 
	 * @param string $func
	 * @param array $args
	 * @return mixed
	 */
	public static function __callStatic($func, $args)
	{
		$type_ar = array
		(
			"blob",
			"bool",
			"date",
			"email",
			"file",
			"float",
			"html",
			"identified",
			"image",
			"int",
			"md5",
			"price",
			"string",
			"text",
			"timestamp",
			"uint",
			"url",
			"path"
		);
		
		if(!in_array($func, $type_ar))
		{
			throw new Exception("Не найден тип \"{$func}\".");
		}
		
		if (count($args) != 1)
		{
			throw new Exception("Аргументы функции заданы неверно.");
		}
		
		try
		{
			if($func != "blob")
			{
				$args[0] = (string) $args[0];
				self::check($args[0]);
			}
			
			call_user_func("self::_".$func, $args[0]);
		}
		catch (Exception $e)
		{
			self::$error = $e->getMessage();
			return false;
		}
		
		return true;
	}

	/**
	 * Общая проверка и удаление пробельных символов
	 * 
	 * @param string $str
	 * @param string $trim
	 * @return bool
	 */
	public static function check(&$str, $trim=true)
	{
		$str = (string) $str;

		/* Пустая строка */
		if ($trim)
		{
			$str = trim($str);
			if (mb_strlen($str) < 1)
			{
				throw new Exception("Пустая строка.");
			}
		}

		/* Строка с нулевым символом */
		$str_temp = $str;
		$strlen_before = mb_strlen($str_temp);
		$str_temp = str_replace(chr(0), '', $str_temp);
		$strlen_after = mb_strlen($str_temp);
		if ($strlen_before != $strlen_after)
		{
			throw new Exception("Нулевой символ.");
		}

		/* Бинарная строка, либо символы не в UTF-8 */
		$result = mb_detect_encoding($str, "UTF-8");
		if ($result === false)
		{
			throw new Exception("Бинарная строка, либо символы не в UTF-8.");
		}
		
		/* Превышает допустимый размер */
		if(mb_strlen($str, "latin1") > self::$max_size)
		{
			throw new Exception("Слишком большая строка.");
		}

		return true;
	}

	/**
	 * Бинарная строка
	 * 
	 * @param string $str
	 * @return boolean
	 */
	private static function _blob($str)
	{
		if(mb_strlen($str, "latin1") > self::$max_size)
		{
			throw new Exception("Слишком большая строка.");
		}
		
		return true;
	}
	
	/**
	 * Булёвое значение
	 * 
	 * @param string $str
	 * @return boolean 
	 */
	private static function _bool($str)
	{
		if($str !== "0" and $str !== "1")
		{
			throw new Exception("Необходимо указать \"0\" или \"1\".");
		}
		
		return true;
	}
	
	/**
	 * Дата в формате dd.mm.YYYY
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _date($str)
	{
		if (!preg_match("#^([\d]{2})\.([\d]{2})\.([\d]{4})$#isu", $str, $sovpal))
		{
			throw new Exception("Недопустимые символы.");
		}

		$day = (int) $sovpal[1];
		$month = (int) $sovpal[2];
		$year = (int) $sovpal[3];

		if ($year < 1000)
		{
			throw new Exception("Год указан неверно.");
		}

		if ($month < 1 or $month > 12)
		{
			throw new Exception("Месяц указан неверно.");
		}

		if ($day < 1)
		{
			throw new Exception("День указан неверно.");
		}

		if (in_array($month, array(1, 3, 5, 7, 8, 10, 12)))
		{
			if ($day > 31)
			{
				throw new Exception("День указан неверно.");
			}
		}
		elseif (in_array($month, array(4, 6, 9, 11)))
		{
			if ($day > 30)
			{
				throw new Exception("День указан неверно.");
			}
		}
		else
		{
			if (($year % 4) == 0)
			{
				if ($day > 29)
				{
					throw new Exception("День задан неверно.");
				}
			}
			else
			{
				if ($day > 28)
				{
					throw new Exception("День задан неверно.");
				}
			}
		}

		return true;
	}
	
	/**
	 * Почтовый ящик
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _email($str)
	{
		self::check($str);

		if (!preg_match("#^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-\.]+\.[a-z]{2,}$#isu", $str))
		{
			throw new Exception("Допускается строка в формате [a-z]@[a-z].[a-z].");
		}

		return true;
	}
	
	/**
	 * Файл
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _file($str)
	{
		if (!preg_match("#^[a-z0-9_-]+\.[a-z0-9]+$#isu", $str))
		{
			throw new Exception("Неодпустимые символы.");
		}

		return true;
	}
	
	/**
	 * Число с плавающей запятой
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _float($str)
	{
		if (!is_numeric($str))
		{
			throw new Exception("Не является числом.");
		}

		$pos = strpos($str, ".");
		if ($pos === false)
		{
			throw new Exception("Целое число.");
		}

		return true;
	}
	
	/**
	 * Строка без содержания тега <script>
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _html($str)
	{
		$result = mb_strpos($str, "<script", 0);
		if ($result !== false)
		{
			throw new Exception("Наличие тега «script».");
		}

		return true;
	}
	
	/**
	 * Идентификатор
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _identified($str)
	{
		$str = mb_strtolower($str);
		$str = strtr($str, "abcdefghijklmnopqrstuvwxyz_0123456789 ", "                                     _");
		if(strlen(trim($str)) != 0)
		{
			throw new Exception("Допускаются символы: a-z,0-9,\"_\" .");
		}
		
		return true;
	}
	
	/**
	 * Наименование файла рисунка
	 * 
	 * @param string $str
	 * @return boolean 
	 */
	private static function _image($str)
	{
		if (!preg_match("#^[a-z0-9_\-.]+\.(gif|jpg|png)+$#isu", $str))
		{
			throw new Exception("Допускаются символы a-z,0-9,\"_\", \".\" в наименовании и расширение gif, jpg, png.");
		}
				
		return true;
	}
	
	/**
	 * Число со знаком
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _int($str)
	{
		if (!is_numeric($str))
		{
			throw new Exception("Не является числом.");
		}

		return true;
	}
	
	/**
	 * Строка MD5
	 * 
	 * @param string $str
	 * @return boolean 
	 */
	private static function _md5($str)
	{
		if (!preg_match("#^[a-z0-9]+$#isu", $str))
		{
			throw new Exception("Допускаются символы a-z,0-9.");
		}

		if(mb_strlen($str, "latin1") != 32)
		{
			throw new Exception("Строка должна содержать 32 символа.");
		}
				
		return true;
	}
	
	/**
	 * Цена - два числа после запятой всегда положительная
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _price($str)
	{
		if (!is_numeric($str))
		{
			throw new Exception("Не является числом.");
		}

		$pos = strpos($str, ".");
		if ($pos === false)
		{
			throw new Exception("Целое число.");
		}

		if (substr($str, -3, 1) != ".")
		{
			throw new Exception("Необходимо две цифры после запятой.");
		}

		return true;
	}
	
	/**
	 * Строка не более 255 символов, и без пробельных символов
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _string($str)
	{
		$result = strpbrk($str, "\n\r\t\v\f\$\\");
		if ($result !== false)
		{
			throw new Exception("Пробельные символы.");
		}

		$result = strpbrk($str, "><");
		if ($result !== false)
		{
			throw new Exception("HTML-символы.");
		}

		if (mb_strlen($str) > 255)
		{
			throw new Exception("Большая строка.");
		}

		return true;
	}
	
	/**
	 * Cтрока без html-тегов
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _text($str)
	{
		$result = strpbrk($str, "><");
		if ($result !== false)
		{
			throw new Exception("HTML-символы.");
		}

		return true;
	}
	
	/**
	 * Дата и время в формате timestamp
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _timestamp($str)
	{
		$result = strtotime($str);
		if ($result === false)
		{
			throw new Exception("Не соответствует формату TIMESTAMP.");
		}

		return true;
	}

	/**
	 * Число без знака
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _uint($str)
	{
		if (!is_numeric($str))
		{
			throw new Exception("Не является числом.");
		}

		$str = (int) $str;

		if ($str != abs($str))
		{
			throw new Exception("Отрицательное число.");
		}

		return true;
	}

	/**
	 * Урл
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _url($str)
	{
		if (!preg_match("#^[a-zа-я0-9\_]+$#isu", $str))
		{
			throw new Exception("Недопустимые символы.");
		}

		return true;
	}
	
	/**
	 * Путь к файлу или каталогу
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _path($str)
	{
		/* Символ "." */
		if ($str == "." or $str == "/")
		{
			return true;
		}
		
		/* Срезаем символы слэша в начале и конце */
		if (mb_substr($str, 0, 1) == "/")
		{
			$str = mb_substr($str, 1, mb_strlen($str) - 1);
		}

		if (mb_substr($str, mb_strlen($str) - 1, 1) == "/")
		{
			$str = mb_substr($str, 0, mb_strlen($str) - 1);
		}

		/* Разбор */
		$str_ar = explode("/", $str);
		foreach ($str_ar as $val)
		{
			/* Указание в пути ".." и "." */
			if ($val == "." or $val == "..")
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Использовать имя файла как \"..\" и \".\" запрещено.");
			}

			/* Строка с начальными или конечными пробелами */
			$strlen = mb_strlen($val);
			$strlen_trim = mb_strlen(trim($val));
			if ($strlen != $strlen_trim)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Пробелы в начале или в конце имени файла.");
			}

			/* Не указано имя файла */
			$val_trim = trim($val);
			if (mb_strlen($val_trim) < 1)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Не задано имя файла.");
			}
		}
	}
}

?>