<?php

/**
 * Checker Field - проверщик полей формы
 */
class Chf
{

	private static $error = "";

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
		if(!in_array($func, array("int","uint","float","price","string","text","html","identified","file","url","email","date","timestamp","path")))
		{
			throw new Exception("Не найден тип \"{$func}\".");
		}
		
		if (count($args) != 1)
		{
			throw new Exception("Аргументы функции заданы неверно.");
		}
		
		try
		{
			self::check($args[0]);
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
			if (mb_strlen($str, "UTF-8") < 1)
			{
				throw new Exception("Пустая строка.");
			}
		}

		/* Строка с нулевым символом */
		$str_temp = $str;
		$strlen_before = mb_strlen($str_temp, "UTF-8");
		$str_temp = str_replace(chr(0), '', $str_temp);
		$strlen_after = mb_strlen($str_temp, "UTF-8");
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

		if (mb_strlen($str, "UTF-8") > 255)
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
	 * Строка без содержания тега <script>
	 * 
	 * @param string $str
	 * @return bool
	 */
	private static function _html($str)
	{
		$result = mb_strpos($str, "<script", 0, "UTF-8");
		if ($result !== false)
		{
			throw new Exception("Наличие тега <script>.");
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
		if (!preg_match("#^[a-z0-9_]+$#isu", $str))
		{
			throw new Exception("Допускаются символы: a-z,0-9,\"_\" .");
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
	 * Урл
	 * 
	 * @param string $str
	 * @return bool 
	 */
	private static function _url($str)
	{
		if (!preg_match("#^[\w\/]+$#isu", $str))
		{
			throw new Exception("Недопустимые символы.");
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
			throw new Exception("Недопустимые символы.");
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
		if (mb_substr($str, 0, 1, "UTF-8") == "/")
		{
			$str = mb_substr($str, 1, mb_strlen($str, "UTF-8") - 1, "UTF-8");
		}

		if (mb_substr($str, mb_strlen($str, "UTF-8") - 1, 1, "UTF-8") == "/")
		{
			$str = mb_substr($str, 0, mb_strlen($str, "UTF-8") - 1, "UTF-8");
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
			$strlen = mb_strlen($val, "UTF-8");
			$strlen_trim = mb_strlen(trim($val), "UTF-8");
			if ($strlen != $strlen_trim)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Пробелы в начале или в конце имени файла.");
			}

			/* Не указано имя файла */
			$val_trim = trim($val);
			if (mb_strlen($val_trim, "UTF-8") < 1)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Не задано имя файла.");
			}
		}
	}
}

?>