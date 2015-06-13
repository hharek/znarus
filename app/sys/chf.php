<?php
/**
 * Checker Field - проверщик полей формы на соответствие типу
 */
class Chf
{
	/**
	 * Текст последней ошибки
	 * 
	 * @var string
	 */
	private static $_error_last = "";

	/**
	 * Функции
	 * 
	 * @param string $func
	 * @param array $args
	 * @return mixed
	 */
	public static function __callStatic($func, $args)
	{
		/* Существует ли указанный тип */
		if (!method_exists(get_class(), "_" . $func))
		{
			throw new Exception("Тип «{$func}» отсутствует.");
		}

		/* Не указана строка */
		if (count($args) === 0)
		{
			throw new Exception("Не указана строка для проверки.");
		}

		/* Много аргументов */
		if (count($args) > 1)
		{
			throw new Exception("Необходимо указать только одну строку для проверки.");
		}

		try
		{
			/* Общая проверка */
			self::_check($args[0]);

			/* Проверка на соответствие типу */
			call_user_func("self::_" . $func, $args[0]);
		}
		catch (Exception $e)
		{
			self::$_error_last = $e->getMessage();

			return false;
		}

		return true;
	}

	/**
	 * Текст последней ошибки
	 * 
	 * @return string
	 */
	public static function error()
	{
		return self::$_error_last;
	}

	/**
	 * Общая проверка
	 * 
	 * @param string $str
	 */
	private static function _check(&$str)
	{
		/* Преобразуем в строку */
		if (is_int($str) or is_float($str) or is_string($str))
		{
			$str = (string) $str;
		}
		elseif (is_bool($str))
		{
			if ($str === true)
			{
				$str = "1";
			}
			elseif ($str === false)
			{
				$str = "0";
			}
		}
		else
		{
			throw new Exception("Не является скалярной переменной.");
		}

		/* Пустая строка */
		if (trim($str) === "")
		{
			throw new Exception("Пустая строка");
		}

		/* Нулевой символ */
		if (strlen($str) !== strlen(str_replace(chr(0), "", $str)))
		{
			throw new Exception("Обнаружен нулевой символ.");
		}

		/* Символы не в UTF-8 */
		if (mb_detect_encoding($str, "UTF-8") === false)
		{
			throw new Exception("Бинарная строка, либо символы не в UTF-8.");
		}
	}

	/**
	 * Идентификатор
	 * 
	 * @param string $str
	 */
	private static function _identified($str)
	{
		if (ctype_alnum(str_replace("_", "", $str)) === false)
		{
			throw new Exception("Допускаются символы: a-z,0-9,\"_\" .");
		}
	}

	/**
	 * Строка не более 255 символов, и без пробельных символов
	 * 
	 * @param string $str
	 */
	private static function _string($str)
	{
		if (strpbrk($str, "\n\r\t\v\f") !== false)
		{
			throw new Exception("Недопустимые символы.");
		}

		if (strpbrk($str, "><") !== false)
		{
			throw new Exception("HTML-символы.");
		}

		if (mb_strlen($str) > 255)
		{
			throw new Exception("Большая строка.");
		}
	}

	/**
	 * Cтрока без html-тегов
	 * 
	 * @param string $str
	 */
	private static function _text($str)
	{
		if (strpbrk($str, "><") !== false)
		{
			throw new Exception("HTML-символы.");
		}
	}

	/**
	 * Строка без содержания тега <script>
	 * 
	 * @param string $str
	 */
	private static function _html($str)
	{
		$str = mb_strtolower($str);
		if (mb_strpos($str, "<script") !== false)
		{
			throw new Exception("Наличие тега «script».");
		}
	}

	/**
	 * Число со знаком
	 * 
	 * @param string $str
	 */
	private static function _int($str)
	{
		if (!is_numeric($str))
		{
			throw new Exception("Не является числом.");
		}
		
		if (strpos($str, ".") !== false)
		{
			throw new Exception("Тип float.");
		}
	}

	/**
	 * Число без знака
	 * 
	 * @param string $str
	 */
	private static function _uint($str)
	{
		self::_int($str);

		$str = (int) $str;

		if ($str !== abs($str))
		{
			throw new Exception("Отрицательное число.");
		}
	}

	/**
	 * Булёвое значение
	 * 
	 * @param string $str
	 */
	private static function _bool($str)
	{
		if ($str !== "0" and $str !== "1")
		{
			throw new Exception("Необходимо указать «0» или «1».");
		}
	}

	/**
	 * Почтовый ящик
	 * 
	 * @param string $str
	 */
	private static function _email($str)
	{
		if (!filter_var($str, FILTER_VALIDATE_EMAIL))
		{
			throw new Exception("Не прошёл валидацию.");
		}
	}

	/**
	 * Цена - два числа после точки всегда положительная
	 * 
	 * @param string $str
	 */
	private static function _price($str)
	{
		if (!is_numeric($str))
		{
			throw new Exception("Не является числом.");
		}

		if (strpos($str, ".") === false)
		{
			throw new Exception("Целое число.");
		}

		if (substr($str, -3, 1) !== ".")
		{
			throw new Exception("Необходимо две цифры после точки.");
		}
		
		if ((int)$str !== abs((int)$str))
		{
			throw new Exception("Отрицательное число.");
		}
	}

	/**
	 * Дата и время в формате timestamp
	 * 
	 * @param string $str
	 */
	private static function _date($str)
	{
		if (strtotime($str) === false)
		{
			throw new Exception("Не является строкой даты или времени.");
		}
	}

	/**
	 * Часть урла
	 * 
	 * @param string $str
	 */
	private static function _url_part($str)
	{
		/* В нижний регистра */
		$str = mb_strtolower($str);
		
		/* «.» или «..» */
		if ($str === "." or $str === "..")
		{
			throw new Exception("Урл не может быть указан как «.» или «..».");
		}
		
		/* Недопустимые символы */
		$str = strtr
		(
			$str, 
			[
				'0'=>'', '1'=>'', '2'=>'', '3'=>'', '4'=>'', '5'=>'', '6'=>'', '7'=>'', '8'=>'', '9'=>'',
				'a'=>'', 'b'=>'', 'c'=>'', 'd'=>'', 'e'=>'', 'f'=>'', 'g'=>'', 'h'=>'', 'i'=>'', 'j'=>'',
				'k'=>'', 'l'=>'', 'm'=>'', 'n'=>'', 'o'=>'', 'p'=>'', 'q'=>'', 'r'=>'', 's'=>'', 't'=>'',
				'u'=>'', 'v'=>'', 'w'=>'', 'x'=>'', 'y'=>'', 'z'=>'', 
				'а'=>'', 'б'=>'', 'в'=>'', 'г'=>'', 'д'=>'', 'е'=>'', 'ё'=>'', 'ж'=>'', 'з'=>'', 'и'=>'',
				'й'=>'', 'к'=>'', 'л'=>'', 'м'=>'', 'н'=>'', 'о'=>'', 'п'=>'', 'р'=>'', 'с'=>'', 'т'=>'', 
				'у'=>'', 'ф'=>'', 'х'=>'', 'ц'=>'', 'ч'=>'', 'ш'=>'', 'щ'=>'', 'ъ'=>'', 'ы'=>'', 'ь'=>'',
				'э'=>'', 'ю'=>'', 'я'=>'',
				'_'=>'', '-'=>'', '.'=>'', ' '=>'_'
			]
		);
		
		if ($str !== "")
		{
			throw new Exception("Допускаются символы: 0-9,a-z,а-я,«_»,«-»,«.» .");
		}
	}

	/**
	 * Путь урла
	 * 
	 * @param string $str
	 */
	private static function _url_path($str)
	{
		/* Срезаем символы слэша в начале и конце */
		if (mb_substr($str, 0, 1) === "/")
		{
			$str = mb_substr($str, 1, mb_strlen($str) - 1);
		}

		if (mb_substr($str, mb_strlen($str) - 1, 1) === "/")
		{
			$str = mb_substr($str, 0, mb_strlen($str) - 1);
		}
		
		/* Разбор */
		$str_ar = explode("/", $str);
		foreach ($str_ar as $val)
		{
			self::_url_part($val);
		}
	}
	
	/**
	 * Урл
	 * 
	 * @param string $str
	 */
	private static function _url($str)
	{
		$parse_url = parse_url($str);
		
		if (!is_string($str))
		{
			throw new Exception("Не является строкой");
		}
		
		if ($parse_url === false)
		{
			throw new Exception("Не прошёл парсинг");
		}
		
		if (!empty($parse_url['path']))
		{
			self::_url_path($parse_url['path']);
		}
		
		if (!empty($parse_url['query']))
		{
			self::_string($parse_url['query']);
		}
	}

	/**
	 * Строка с тэгами через запятую
	 * 
	 * @param string $str
	 */
	private static function _tags($str)
	{
		/* В нижний регистр */
		$str = mb_strtolower($str);
		
		/* Проверка на наличие недопустимых символов */
		$str_trim = strtr
		(
			$str, 
			[
				'0'=>'', '1'=>'', '2'=>'', '3'=>'', '4'=>'', '5'=>'', '6'=>'', '7'=>'', '8'=>'', '9'=>'',
				'a'=>'', 'b'=>'', 'c'=>'', 'd'=>'', 'e'=>'', 'f'=>'', 'g'=>'', 'h'=>'', 'i'=>'', 'j'=>'',
				'k'=>'', 'l'=>'', 'm'=>'', 'n'=>'', 'o'=>'', 'p'=>'', 'q'=>'', 'r'=>'', 's'=>'', 't'=>'',
				'u'=>'', 'v'=>'', 'w'=>'', 'x'=>'', 'y'=>'', 'z'=>'', 
				'а'=>'', 'б'=>'', 'в'=>'', 'г'=>'', 'д'=>'', 'е'=>'', 'ё'=>'', 'ж'=>'', 'з'=>'', 'и'=>'',
				'й'=>'', 'к'=>'', 'л'=>'', 'м'=>'', 'н'=>'', 'о'=>'', 'п'=>'', 'р'=>'', 'с'=>'', 'т'=>'', 
				'у'=>'', 'ф'=>'', 'х'=>'', 'ц'=>'', 'ч'=>'', 'ш'=>'', 'щ'=>'', 'ъ'=>'', 'ы'=>'', 'ь'=>'',
				'э'=>'', 'ю'=>'', 'я'=>'',
				','=>'', ' '=>''
			]
		);
		
		if ($str_trim !== "")
		{
			throw new Exception("Допускаются символы: 0-9,a-z,а-я,«,».");
		}
		
		/* Проверка тэгов на пустоту */
		$ar = explode(",", $str);
		if (empty($ar))
		{
			throw new Exception("Не указано ни одного тэга");
		}

		foreach ($ar as $key => $val)
		{
			$val = trim($val);
			if (empty($val))
			{
				throw new Exception("Пустой тэг или лишняя зяпятая");
			}

			$ar[$key] = $val;
		}

		/* Повторяющиеся тэги */
		if (count($ar) !== count(array_unique($ar)))
		{
			throw new Exception("Повторяющиеся тэги");
		}
	}

	/**
	 * Путь к файлу или каталогу
	 * 
	 * @param string $str
	 */
	private static function _path($str)
	{
		/* Символ "." */
		if ($str === "." or $str === "/")
		{
			return true;
		}

		/* Срезаем символы слэша в начале и конце */
		if (mb_substr($str, 0, 1) === "/")
		{
			$str = mb_substr($str, 1, mb_strlen($str) - 1);
		}

		if (mb_substr($str, mb_strlen($str) - 1, 1) === "/")
		{
			$str = mb_substr($str, 0, mb_strlen($str) - 1);
		}

		/* Разбор */
		$str_ar = explode("/", $str);
		foreach ($str_ar as $val)
		{
			/* Указание в пути ".." и "." */
			if ($val === "." or $val === "..")
			{
				throw new Exception("Использовать имя файла как «..» и «.» запрещено.");
			}

			/* Строка с начальными или конечными пробелами */
			if (mb_strlen($val) !== mb_strlen(trim($val)))
			{
				throw new Exception("Пробелы в начале или в конце имени файла.");
			}

			/* Не указано имя файла */
			if (trim($val) === "")
			{
				throw new Exception("Не задано имя файла.");
			}
		}
	}
	
	/**
	 * IP-адрес
	 * 
	 * @param string $str
	 */
	private static function _ip($str)
	{
		if (!filter_var($str, FILTER_VALIDATE_IP))
		{
			throw new Exception("Не прошёл валидацию.");
		}
	}
}
?>