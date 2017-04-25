<?php
/**
 * Table Manager. Field Type - Типы полей таблицы
 */
class Type
{
	/**
	 * Текст последней ошибки
	 * 
	 * @var string
	 */
	private static $_last_error;
	
	/**
	 * Типы
	 * 
	 * @var array
	 */
	private static $_ = 
	[
		"identified" => 
		[
			"sql_type"	=> "varchar(127)",
			"prepare"	=> "strtolower"
		],
		"string" => 
		[
			"sql_type"	=> "varchar(255)",
			"equal"		=> "ilike"
		],
		"text" => 
		[
			"sql_type"	=> "text",
			"equal"		=> "ilike",
			"null"		=> true,
			"lite"		=> false
		],
		"html" => 
		[
			"sql_type"	=> "text",
			"equal"		=> "ilike",
			"null"		=> true,
			"lite"		=> false
		],
		"int" => 
		[
			"sql_type"	=> "int",
			"php_type"	=> "int"
		],
		"uint" => 
		[
			"sql_type"	=> "int",
			"php_type"	=> "int"
		],
		"boolean" => 
		[
			"sql_type"		=> "boolean",
			"sql_select"	=> "\"{identified}\"::int as \"{identified}|{php_type}\"",
			"prepare"		=> ["self", "_prepare_boolean"],
			"php_type"		=> "bool"
		],
		"bool" => 
		[
			"sql_type"		=> "boolean",
			"sql_select"	=> "\"{identified}\"::int as \"{identified}|{php_type}\"",
			"prepare"		=> ["self", "_prepare_boolean"],
			"php_type"		=> "bool"
		],
		"email" => 
		[
			"sql_type"	=> "varchar(127)",
			"prepare"	=> "strtolower"
		],
		"price" => 
		[
			"sql_type"	=> "numeric(10,2)",
			"prepare"	=> ["self", "_prepare_price"],
			"php_type"	=> "float"
		],
		"date" => 
		[
			"sql_type"	=> "date",
			"prepare"	=> ["self", "_prepare_date"]
		],
		"datetime" => 
		[
			"sql_type"	=> "timestamp",
			"prepare"	=> ["self", "_prepare_datetime"]
		],
		"url_part" => 
		[
			"sql_type"	=> "varchar(255)",
			"prepare"	=> "mb_strtolower",
			"equal"		=> "like"
		],
		"url_path" => 
		[
			"sql_type"	=> "varchar(255)",
			"prepare"	=> "mb_strtolower",
			"equal"		=> "like"
		],
		"url" => 
		[
			"sql_type"	=> "varchar(255)",
			"prepare"	=> "mb_strtolower",
			"equal"		=> "like"
		],
		"tags" => 
		[
			"sql_type"	=> "text",
			"prepare"	=> "mb_strtolower",
			"equal"		=> "like",
			"null"		=> true,
			"lite"		=> false
		],
		"path" => 
		[
			"sql_type"	=> "varchar(255)",
			"prepare"	=> "mb_strtolower",
			"equal"		=> "like"
		],
		"ip" => 
		[
			"sql_type"	=> "varchar(15)"
		],
		"serial" => 
		[
			"sql_type"		=> "int",
			"sql_create"	=> "\"{identified}\" serial NOT NULL",
			"require"		=> false,
			"php_type"		=> "int"
		],
		"id" => 
		[
			"sql_type"	=> "int",
			"seq"		=> "{table}_seq",
			"seq_type"	=> "next",
			"seq_owned"	=> true,
			"primary"	=> true,
			"require"	=> false,
			"php_type"	=> "int"
		],
		"order" => 
		[
			"sql_type"	=> "int",
			"seq"		=> "{table}_seq",
			"seq_type"	=> "current",
			"require"	=> false,
			"php_type"	=> "int",
			"order"		=> "asc"
		],
		"enum" =>
		[
			"sql_type"			=> "varchar(255)",
			"sql_constraint"	=> "CONSTRAINT \"{table}_{identified}_check\" CHECK (\"{identified}\" IN ({enum_values}))",
			"equal"				=> "like"
		],
		"json" => 
		[
			"sql_type"	=> "jsonb",
			"null"		=> true,
			"lite"		=> false
		],
		"last_modified" => 
		[
			"sql_type"		=> "timestamp",
			"sql_default"	=> "now()",
			"require"		=> false
		]
	];
	
	/**
	 * Проверка поля
	 * 
	 * @param string $type
	 * @param string $str
	 * @return boolean
	 */
	public static function check(string $type, string $str) : bool
	{
		try
		{
			/* Доступный тип */
			if (!array_key_exists($type, self::$_))
			{
				throw new Exception("Указанный тип «{$type}» отсутствует.");
			}

			/* Пустая строка */
			if (trim($str) === "")
			{
				throw new Exception("Пустая строка");
			}

			/* Нулевой символ */
			if (strpos($str, chr(0)) !== false)
			{
				throw new Exception("Обнаружен нулевой символ.");
			}

			/* Символы не в UTF-8 */
			if (mb_detect_encoding($str, "UTF-8") === false)
			{
				throw new Exception("Бинарная строка, либо символы не в UTF-8.");
			}

			/* Проверка по типу */
			self::{"_check_" . $type}($str);
			
			return true;
		}
		catch (Exception $e)
		{
			self::$_last_error = $e->getMessage();
			
			return false;
		}
	}
	
	/**
	 * Проверить тип enum
	 * 
	 * @param string $str
	 * @param array $enum_values
	 * @return bool
	 */
	public static function check_enum(string $str, array $enum_values) : bool
	{
		if (!in_array($str, $enum_values))
		{
			self::$_last_error = "Доступные значения: " . implode(", ", $enum_values) . ".";
			return false;
		}
		
		return true;
	}
	
	/**
	 * Текст последней ошибки
	 * 
	 * @return string
	 */
	public static function get_last_error() : string
	{
		return self::$_last_error;
	}
	
	/**
	 * Доступен ли указанный тип
	 * 
	 * @param string $type
	 */
	public static function is(string $type) : bool
	{
		return isset(self::$_[$type]);
	}
	
	/**
	 * Получить сведения по типу
	 * 
	 * @param string $type
	 * @return array
	 */
	public static function get(string $type) : array
	{
		return self::$_[$type];
	}

	/**
	 * Получить SQL для запроса CREATE 
	 * 
	 * @param string $schema
	 * @param string $table
	 * @param array $field
	 * @return string
	 */
	public static function get_sql_create(string $schema, string $table, array $field) : string
	{
		/* Имя счётчика */
		$seq = "";
		if (!empty($field['seq']))
		{
			$seq = strtr($field['seq'], 
			[
				"{identified}" => $field['identified'],
				"{table}" => $schema . "." . $table
			]);
			$seq = strtolower($seq);
		}
		
		/* Свой SQL-create */
		if (isset($field['sql_create']))
		{
			return strtr($field['sql_create'], 
			[
				"{identified}" => $field['identified'],
				"{table}" => $table,
				"{seq}" => $seq
			]) ;
		}
		
		/* Шаблон */
		$sql = "\"{identified}\" {sql_type}{null}{default}";
		
		$data = [];
		$data['{identified}'] = $field['identified'];

		/* SQL тип */
		$data['{sql_type}'] = self::$_[$field['type']]['sql_type'];

		/* NULL */
		$data['{null}'] = "";
		if (isset($field['null']) and $field['null'] === true)
		{
			$data['{null}'] = " NULL";
		}
		else
		{
			$data['{null}'] = " NOT NULL";
		}

		/* DEFAULT */
		$data['{default}'] = "";
		
		/* Указан «sql_default» */
		if (isset($field['sql_default']))
		{
			$data['{default}'] = " DEFAULT {$field['sql_default']}";
		}
		/* Указан «seq». Поле является счётчиком */
		elseif (isset($field['seq']))
		{
			$seq_type = "next";
			if (isset($field['seq_type']))
			{
				$seq_type = $field['seq_type'];
			}
			
			if ($seq_type === "next")
			{
				$data['{default}'] = str_replace("{seq}", $seq, " DEFAULT nextval('{seq}')");
			}
			elseif ($seq_type === "current")
			{
				$data['{default}'] = str_replace("{seq}", $seq, " DEFAULT currval('{seq}')");
			}
		}
		/* Указан «default» */
		elseif (array_key_exists('default', $field))
		{
			if (is_string($field['default']))
			{
				$data['{default}'] = " DEFAULT '" . $field['default'] . "'";
			}
			elseif (is_int($field['default']) or is_float($field['default']))
			{
				$data['{default}'] = " DEFAULT " . $field['default'];
			}
			elseif (is_bool($field['default']))
			{
				$field['default'] = $field['default'] ? "true" : "false";
				$data['{default}'] = " DEFAULT " . $field['default'];
			}
			elseif ($field['default'] === null)
			{
				$data['{default}'] = " DEFAULT NULL";
			}
		}
		
		/* Подстановка */
		$sql = strtr($sql, $data);
		
		return $sql;
	}
	
	/**
	 * Получить SQL для запроса SELECT
	 * 
	 * @param array $field
	 * @return string
	 */
	public static function get_sql_select(array $field) : string
	{
		/* Добавляем к наименование */
		$php_type = "string";
		if (isset($field['php_type']))
		{
			$php_type = $field['php_type'];
		}
		
		$sql = 
<<<SQL
"{$field['identified']}" as "{$field['identified']}|{$php_type}"
SQL;
	
		/* Свой SELECT */
		if (isset($field['sql_select']))
		{
			$sql = strtr($field['sql_select'], 
			[
				"{identified}" => $field['identified'],
				"{php_type}" => $field['php_type']
			]);
		}
		
		return $sql;
	}
	
	/**
	 * Получить SQL для запроса на уникальность в блоке WHERE
	 * 
	 * @param array $field
	 * @param int $num
	 * @return string
	 */
	public static function get_sql_where(array $field, int $num = 1, bool $not = false) : string
	{
		$type = $field['type'];
		
		/* Оператор равенства */
		$equal = "=";
		if (isset(self::$_[$type]['equal']))
		{
			$equal = self::$_[$type]['equal'];
		}
		
		/* NOT */
		if ($not)
		{
			switch ($equal)
			{
				case "=":		$equal = "!=";			break;
				case "like":	$equal = "NOT LIKE";	break;
				case "ilike":	$equal = "NOT ILIKE";	break;
			}
		}
		
		/* NOT NULL */
		if (!isset($field['null']) or $field['null'] === false)
		{
			$sql = "\"{$field['identified']}\" {$equal} \${$num}";
		}
		/* NULL */
		else
		{
			$sql_type = self::$_[$type]['sql_type'];
			
			$sql = 
<<<SQL
(
		(
			\${$num}::{$sql_type} IS NULL AND
			"{$field['identified']}" IS NULL
		) OR
		(
			\${$num}::{$sql_type} IS NOT NULL AND
			"{$field['identified']}" {$equal} \${$num}
		)
	)
SQL;
		}
		
		return $sql;
	}
	
	/**
	 * Подготовить значение перед выполнение SQL запроса
	 * 
	 * @param string $value
	 * @param string $func
	 * @return string
	 */
	public static function prepare (string $value, $func) : string
	{
		return call_user_func($func, $value);
	}

	/**
	 * Идентификатор
	 * 
	 * @param string $str
	 */
	private static function _check_identified(string $str)
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
	private static function _check_string(string $str)
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
	private static function _check_text(string $str)
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
	private static function _check_html(string $str)
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
	private static function _check_int(string $str)
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
	private static function _check_uint(string $str)
	{
		self::_check_int($str);

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
	private static function _check_boolean(string $str)
	{
		if ($str !== "0" and $str !== "1")
		{
			throw new Exception("Необходимо указать «0» или «1».");
		}
	}
	
	/**
	 * Булёвое значение
	 * 
	 * @param string $str
	 */
	private static function _check_bool(string $str)
	{
		self::_check_boolean($str);
	}
	
	/**
	 * Почтовый ящик
	 * 
	 * @param string $str
	 */
	private static function _check_email(string $str)
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
	private static function _check_price(string $str)
	{
		$str = str_replace(",", ".", $str);
		
		if (!is_numeric($str))
		{
			throw new Exception("Не является числом.");
		}

		if ((int)$str !== abs((int)$str))
		{
			throw new Exception("Отрицательное число.");
		}
	}

	/**
	 * Дата
	 * 
	 * @param string $str
	 */
	private static function _check_date(string $str)
	{
		if (strtotime($str) === false)
		{
			throw new Exception("Не является строкой даты или времени.");
		}
	}
	
	/**
	 * Дата и время
	 * 
	 * @param string $str
	 */
	private static function _check_datetime(string $str)
	{
		self::_check_date($str);
	}

	/**
	 * Часть урла
	 * 
	 * @param string $str
	 */
	private static function _check_url_part(string $str)
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
	private static function _check_url_path(string $str)
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
			self::_check_url_part($val);
		}
	}
	
	/**
	 * Урл
	 * 
	 * @param string $str
	 */
	private static function _check_url(string $str)
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
			self::_check_url_path($parse_url['path']);
		}
		
		if (!empty($parse_url['query']))
		{
			self::_check_string($parse_url['query']);
		}
	}

	/**
	 * Строка с тэгами через запятую
	 * 
	 * @param string $str
	 */
	private static function _check_tags(string $str)
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
				','=>'', ' '=>'', '_'=> ''
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
	private static function _check_path(string $str)
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
	private static function _check_ip(string $str)
	{
		if (!filter_var($str, FILTER_VALIDATE_IP))
		{
			throw new Exception("Не прошёл валидацию.");
		}
	}
	
	/**
	 * Последовательность (int DEFAULT nextval(SEQUENCE) PRIMARY)
	 * 
	 * @param string $str
	 */
	private static function _check_serial(string $str)
	{
		self::_check_uint($str);
	}
	
	/**
	 * Порядковые номер (int DEFAULT nextval(SEQUENCE) PRIMARY)
	 * 
	 * @param string $str
	 */
	private static function _check_id(string $str)
	{
		self::_check_uint($str);
	}
	
	/**
	 * Поле сортировки (int DEFAULT currval(ID SEQUENCE))
	 * 
	 * @param string $str
	 */
	private static function _check_order(string $str)
	{
		self::_check_uint($str);
	}
	
	/**
	 * JSON-закодированная строка
	 * 
	 * @param string $str
	 */
	private static function _check_json(string $str)
	{
		if (strtolower($str) !== "null" and json_decode($str) === null)
		{
			throw new Exception("Не прошёл валидацию.");
		}
	}
	
	/**
	 * HTTP-заголовок «Last-Modified»
	 * 
	 * @param string $str
	 */
	private static function _check_last_modified(string $str)
	{
		self::_check_date($str);
	}

	/**
	 * Подготовить цену перед вставкой
	 * 
	 * @param string $str
	 * @return string
	 */
	private static function _prepare_price (string $str) : string
	{
		return str_replace(",", ".", $str);
	}
	
	/**
	 * Привести дату к формату SQL
	 * 
	 * @param string $str
	 * @return string
	 */
	private static function _prepare_date (string $str) : string
	{
		return date ("Y-m-d", strtotime($str));
	}
	
	/**
	 * Привести дату с временем к формату SQL
	 * 
	 * @param string $str
	 * @return string
	 */
	private static function _prepare_datetime (string $str) : string
	{
		return date ("Y-m-d H:i:s", strtotime($str));
	}
	
	/**
	 * Подготовить булевое значение перед запросом
	 * 
	 * @param string $str
	 * @return string
	 */
	private static function _prepare_boolean (string $str) : string
	{
		return (string)(int)$str;
	}
}
?>