<?php
/**
 * Table Manager - управляющий таблицой
 */
class TM
{
	/**
	 * Ресурс подключения к БД
	 * 
	 * @var resource
	 */
	protected static $_db_conn;
	
	/**
	 * Наименование схемы
	 * 
	 * @var string
	 */
	protected static $_schema = "public";

	/**
	 * Наименование таблицы
	 * 
	 * @var string
	 */
	protected static $_table;
	
	/**
	 * Наименование сущности
	 * 
	 * @var string
	 */
	protected static $_name;

	/**
	 * Поля
	 * 
	 * @var array
	 */
	protected static $_field = [];
	
	/**
	 * Первичные ключи таблиц
	 * 
	 * @var array
	 */
	protected static $_primary = [];
	
	/**
	 * Поля сортировки таблиц
	 * 
	 * @var array
	 */
	protected static $_order = [];
	
	/**
	 * Уникальные ключи таблиц
	 * 
	 * @var array
	 */
	protected static $_unique = [];
	
	/**
	 * Внешние ключи таблиц
	 * 
	 * @var array
	 */
	protected static $_foreign = [];
	
	/**
	 * Список счётчиков, привязанных к таблице
	 * 
	 * @var array
	 */
	protected static $_seq = [];
	
	/**
	 * Другие ограничения
	 * 
	 * @var array
	 */
	protected static $_sql_constraint = [];

	/**
	 * Шаблоны SQL
	 * 
	 * @var array
	 */
	protected static $_sql = 
	[
		"create" => 
<<<SQL
{drop_table}
{sequence_create}
CREATE TABLE {table}
(
{field}{primary}{foreign}{constraint}
);\n
{sequence_owned}
{unique}
{comment_table}
{comment_column}
SQL
,
		"drop_table" =>
<<<SQL
DROP TABLE IF EXISTS {table} CASCADE;\n
SQL
,
		"sequence_create" => 
<<<SQL
CREATE SEQUENCE "{schema}"."{identified}" RESTART;\n
SQL
,		
		"sequence_owned" => 
<<<SQL
ALTER SEQUENCE "{schema}"."{identified}" OWNED BY {table}."{field}";\n
SQL
,		
		"constraint_primary" => 
<<<SQL
,\n\tCONSTRAINT "{table}_PK" PRIMARY KEY ("{field}")
SQL
,
		"constraint_foreign" =>
<<<SQL
,\n\tCONSTRAINT "{key}" FOREIGN KEY ("{identified}")
\t\tREFERENCES {fk_table} ("{fk_field}") {fk_type}
SQL
,
		"index_unique" => 
<<<SQL
CREATE UNIQUE INDEX "{table_name}_{key}" ON {table} ({field});\n
SQL
,
		"index_unique_null" => 
<<<SQL
CREATE UNIQUE INDEX "{table_name}_{key}" ON {table} ({field_all}) WHERE "{field}" IS NOT NULL;
CREATE UNIQUE INDEX "{table_name}_{key}_NULL" ON {table} ({field_all_but}) WHERE "{field}" IS NULL;\n
SQL
,
		"comment_table" => 
<<<SQL
COMMENT ON TABLE {table} IS '{name}';\n
SQL
,
		"comment_column" => 
<<<SQL
COMMENT ON COLUMN {table}."{identified}" IS '{name}';\n
SQL
,
		"is" =>
<<<SQL
SELECT 
	true
FROM 
	{table}
WHERE 
	"{column}" = $1
SQL
,
		
		"get" => 
<<<SQL
SELECT
{field}
FROM
	{table}
WHERE
	"{column}" = $1
SQL
,
		"select" => 
<<<SQL
SELECT
{field}
FROM
	{table}
{where}
{order}
{limit}
SQL
,
		"unique" => 
<<<SQL
SELECT 
	true
FROM 
	{table}
WHERE 
	{where}
SQL
,
		"insert" => 
<<<SQL
INSERT INTO {table} ({field})
VALUES ({values_num})
RETURNING "{primary}"
SQL
,
		"update" => 
<<<SQL
UPDATE {table}
SET 
{field}
WHERE
	"{primary}" = \${num}
SQL
,
		"delete" => 
<<<SQL
DELETE
FROM
	{table}
WHERE
	"{primary}" = \$1
SQL
,

		"count" =>
<<<SQL
SELECT 
	COUNT(*)
FROM
	{table}
{where}
SQL
,
		"order" =>
<<<SQL
SELECT 
	"{primary}", 
	"{order}"
FROM 
	{table}
{where}
ORDER BY 
	"{order}" ASC
SQL
	];
	
	/**
	 * Список таблиц, у которых собраные мета данные
	 * 
	 * @var array
	 */
	protected static $_meta = [];
	
	/**
	 * Проверка на существование по первичному ключу
	 * 
	 * @param string $primary
	 * @param bool $exception
	 * @return bool
	 */
	public static function is(string $primary, bool $exception = true) : bool
	{
		/* Собираем данные */
		static::_meta();
		
		/* Проверка на соответствие типу */
		if (!Type::check(self::$_primary[static::_table()]['type'], $primary))
		{
			if ($exception)
			{
				throw new Exception("Поле «" . self::$_primary[static::_table()]['name'] . "» задано неверно. " . Type::get_last_error());
			}
			else
			{
				return false;
			}
		}
		
		/* SQL */
		$query = self::_get_sql(self::$_sql['is'], 
		[
			"column" => self::$_primary[static::_table()]['identified']
		]);
	
		/* Запрос */
		$result = pg_query_params(static::$_db_conn, $query, [$primary]);
		$count = pg_num_rows($result);
		pg_free_result($result);
		if ($count === 0)
		{
			if ($exception)
			{
				throw new Exception("«" . static::$_name . "» с полем «" . self::$_primary[static::_table()]['identified'] . "» = «" . $primary . "» отсутствует.");
			}
			else
			{
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Выборка по первичному ключу
	 * 
	 * @param string $primary
	 * @return array
	 */
	public static function get(string $primary) : array
	{
		/* Собираем данные */
		static::_meta();
		
		/* Проверка на существование */
		static::is($primary);
		
		/* Формируем запрос */
		$field = "";
		foreach (static::$_field as $key => $f)
		{
			/* Запятая */
			if ($key !== 0)
			{
				$field .= ",\n";
			}

			$field .= "\t" . Type::get_sql_select($f);
		}
		
		/* Основной запрос */
		$query = self::_get_sql(self::$_sql['get'], 
		[
			"field" => $field,
			"column" => self::$_primary[static::_table()]['identified']
		]);
		
		/* Запрос */
		$result = pg_query_params(self::$_db_conn, $query, [$primary]);
		
		/* Данные */
		$row = pg_fetch_assoc($result);
		if ($row === false)
		{
			$row = [];
		}
		
		/* Приводим значения к PHP-типу */
		self::_convert_row_php_type($row);
		
		/* Освобождаем ресурс результата запроса */
		pg_free_result($result);
		
		return $row;
	}
	
	/**
	 * Выборка по условию
	 * 
	 * @param array $where
	 * @param array $field
	 * @param int $page
	 * @param int $limit
	 * @param array $order
	 * @return array
	 */
	public static function select(array $where = [], array $field = [], int $page = 0, int $limit = 0, array $order = []) : array
	{
		/* Собираем данные */
		static::_meta();
		
		/* Проверка поля where */
		$sql_where = ""; $where_info = [];
		if (!empty($where))
		{
			$where_info = static::_data_info($where);
			
			$sql_where .= "WHERE\n";
			static::check($where);
			$param_num = 1;
			foreach ($where_info as $f)
			{
				if ($param_num !== 1)
				{
					$sql_where .= " AND\n";
				}
				
				$sql_where .= "\t" . Type::get_sql_where($f, $param_num);
				$param_num++;
			}
		}
		
		/* Колонки для выборки */
		if (!empty($field))
		{
			if ($field !== array_values($field))
			{
				throw new Exception("Список полей задан неверно, необходимо указать список, к примеру «['ID', 'Name', 'Content']».");
			}
			
			/* Существуют ли указанные колонки */
			$tm_identified = array_column(static::$_field, "identified");
			foreach ($field as $identified)
			{
				if (!in_array($identified, $tm_identified))
				{
					throw new Exception("Таблица «" . static::_table() . "». Поле «{$identified}» отсутствует.");
				}
			}
		}
		/* Колонки для выборки. По умолчанию все */
		else
		{
			$field = array_column(static::$_field, "identified");
		}
		
		/* SQL для полей */
		$field_info = static::_data_info($field);
		$sql_field = "";
		foreach ($field_info as $key => $f)
		{
			if (!in_array($f['identified'], $field))
			{
				continue;
			}
			
			if ($key !== 0)
			{
				$sql_field .= ",\n";
			}

			$sql_field .= "\t" . Type::get_sql_select($f);
		}
		
		/* Order */
		if (!empty($order))
		{
			$tm_identified = array_column(static::$_field, "identified");
			foreach ($order as $identified => $value)
			{
				if (!in_array($identified, $tm_identified))
				{
					throw new Exception("Таблица «" . static::_table() . "». Поле для сортировки «{$identified}» отсутствует.");
				}
				
				$value = strtolower($value);
				if ($value !== "asc" and $value !== "desc")
				{
					throw new Exception("Таблица «" . static::_table() . "». Сортировка должна указываться как «asc» или «desc».");
				}
			}
		}
		/* По умолчанию */
		else
		{
			$order = self::$_order[static::_table()];
		}
		
		/* SQL ORDER */
		$sql_order = "";
		if (!empty($order))
		{
			$sql_order .= "ORDER BY\n"; $order_num = 0;
			foreach ($order as $identified => $value)
			{
				if ($order_num !== 0)
				{
					$sql_order .= ",\n";
				}
				$sql_order .= "\t\"{$identified}\" " . strtoupper($value);
				$order_num++;
			}
		}
		
		/* OFFSET LIMIT */
		$sql_limit = "";
		if ($page > 0 and $limit > 0)
		{
			$offset = ($page -1 ) * $limit;
			$sql_limit .= "OFFSET " . $offset . "\n";
			$sql_limit .= "LIMIT " . $limit;
		}
		
		/* Формируем запрос */
		$query = self::_get_sql(self::$_sql['select'], 
		[
			"field" => $sql_field,
			"where" => $sql_where,
			"order" => $sql_order,
			"limit" => $sql_limit
		]);
		
		/* Подготовим значения WHERE */
		$where_info = self::_prepare_values($where_info);
		$values = array_column($where_info, "value");
		
		/* Запрос */
		$result = pg_query_params(self::$_db_conn, $query, $values);
		$data = pg_fetch_all($result);
		if ($data === false)
		{
			$data = [];
		}
		
		/* Приводим значения к PHP-типу */
		foreach ($data as &$row)
		{
			self::_convert_row_php_type($row);
		}
		
		/* Очищаем ресурс запроса */
		pg_free_result($result);
		
		return $data;
	}
	
	/**
	 * Выборка всех «лёгких» полей
	 * 
	 * @param array $where
	 * @param int $page
	 * @param int $limit
	 * @param array $order
	 * @return array
	 */
	public static function selectl(array $where = [], int $page = 0, int $limit = 0, array $order = []) : array
	{
		/* Собираем данные */
		static::_meta();
		
		/* Отбираем «легкие» поля */
		$fieldl = [];
		foreach (static::$_field as $f)
		{
			if (!isset($f['lite']) or $f['lite'] === true)
			{
				$fieldl[] = $f['identified'];
			}
		}
		
		/* Обычный select */
		return static::select($where, $fieldl, $page, $limit, $order);
	}
	
	/**
	 * Вернуть кол-во строк
	 * 
	 * @param array $where
	 * @return int
	 */
	public static function count(array $where = []) : int
	{
		/* Собираем данные */
		static::_meta();
		
		/* WHERE */
		$sql_where = ""; $where_info = [];
		if (!empty($where))
		{
			$where_info = static::_data_info($where);
			
			$sql_where .= "WHERE\n";
			static::check($where);
			$param_num = 1;
			foreach ($where_info as $f)
			{
				if ($param_num !== 1)
				{
					$sql_where .= " AND\n";
				}
				
				$sql_where .= "\t" . Type::get_sql_where($f, $param_num);
				$param_num++;
			}
		}
		
		/* Подготавливаем значения */
		$where_info = self::_prepare_values($where_info);
		
		/* Запрос */
		$query = self::_get_sql(self::$_sql['count'], ["where" => $sql_where]);
		$result = pg_query_params(self::$_db_conn, $query, array_column($where_info, "value"));
		
		$count = (int)pg_fetch_result($result, 0, 0);
		pg_free_result($result);
		
		return $count;
	}

	/**
	 * Проверка данных
	 * 
	 * @param array $data
	 * @param bool $exception_many
	 */
	public static function check(array $data, bool $exception_many = true) : bool
	{
		/* Собираем данные */
		static::_meta();
		
		/* Массив с ошибками */
		$err = [];
		
		/* Отсутствуют данные */
		if (empty($data))
		{
			return true;
		}
		
		/* Данные по полям */
		$fdata = static::_data_info($data);
		
		/* Проверка */
		foreach ($fdata as $f)
		{
			/* Не проверять */
			if (isset($f['check']) and $f['check'] === false)
			{
				continue;
			}
			
			/* NULL */
			if ($f['value'] === null)
			{
				if (isset($f['null']) and $f['null'] === true)
				{
					continue;
				}
				else
				{
					throw new Exception("Поле «{$f['name']}» не может быть задано как «NULL».");
				}
			}
			
			/* Не является строкой */
			if (!is_scalar($f['value']))
			{
				$error = "Поле «{$f['name']}» задано неверно. Не является строкой.";
				if ($exception_many)
				{
					$err[$f['identified']] = $error;
					continue;
				}
				else
				{
					throw new Exception($error);
				}
			}
			
			/* Преобразуем в строку */
			if (is_bool($f['value'])) 
			{
				$f['value'] = (string)(int)$f['value'];
			}
			else
			{
				$f['value'] = (string)$f['value'];
			}
			
			/* Пустое значение */
			if (trim($f['value']) === "")
			{
				/* Не обязательно для заполения */
				if (isset($f['empty_allow']) and $f['empty_allow'] === true)
				{
					continue;
				}
				/* Обязательно для заполнения */
				else
				{
					$error = "«" . static::$_name . "». Поле «{$f['name']}» не заполнено.";
					if ($exception_many)
					{
						$err[$f['identified']] = $error;
						continue;
					}
					else
					{
						throw new Exception($error);
					}	
				}
			}
			
			/* Проверяем на соответствие типу */
			if 
			(
				($f['type'] !== "enum" and !Type::check($f['type'], $f['value'])) or
				($f['type'] === "enum" and !Type::check_enum($f['value'], $f['enum_values'])) 
			)
			{
				$error = "Поле «{$f['name']}» задано неверно. " . Type::get_last_error();
				if ($exception_many)
				{
					$err[$f['identified']] = $error;
					continue;
				}
				else
				{
					throw new Exception($error);
				}
			}
		}
		
		if (!empty($err))
		{
			throw new Exception_Many($err);
		}
		
		return true;
	}
	
	/**
	 * Проверка на уникальность
	 * 
	 * @param array $data
	 * @param string $primary
	 * @param boolean $exception
	 * @return array
	 */
	public static function unique(array $data, string $primary = null, bool $exception = false) : array
	{
		/* Собираем данные */
		static::_meta();
		
		/* Проверка */
		if (empty($data))
		{
			throw new Exception("Таблица «" . static::_table() . "». Не указаны данные для проверки уникальности.");	
		}
		static::check($data, !$exception);
		$fdata = static::_data_info($data);
		
		if (!empty($primary))
		{
			static::is($primary);
		}
		
		/* SQL полей */
		$where = ""; $num = 1;
		foreach ($fdata as $f)
		{
			if ($num > 1)
			{
				$where .= " AND\n\t";
			}
			
			$where .= Type::get_sql_where($f, $num);
			$num++;
		}
		
		/* PRIMARY */
		if (!empty($primary))
		{
			$where .= " AND\n\t" . Type::get_sql_where(self::$_primary[static::_table()], $num, true);
		}

		/* SQL */
		$query = self::_get_sql(self::$_sql['unique'], ["where" => $where]);
		
		/* Запрос */
		$values = array_values($data);
		if (!empty($primary))
		{
			$values[] = $primary;
		}
		
		$result = pg_query_params(self::$_db_conn, $query, $values);
		
		/* Данные */
		$count = pg_num_rows($result);
		pg_free_result($result);
		if ($count > 0)
		{
			/* Текст ошибки */
			$error = "«" . static::$_name . "» с полем «{$fdata[0]['name']}» : «{$fdata[0]['value']}» уже существует.";
			if (!$exception)
			{
				return [$fdata[0]['identified'] => $error];
			}
			else
			{
				throw new Exception($error);
			}
		}
		
		return [];
	}
	
	/**
	 * Добавить данные
	 * 
	 * @param array $data
	 */
	public static function insert(array $data) : array
	{
		/* Собираем данные */
		static::_meta();
		
		/* Проверка */
		static::check($data);
		
		/* Поля обязательные при вставке */
		foreach (static::$_field as $f)
		{
			if 
			(
				(!isset($f['require']) or $f['require'] === true) and
				!in_array($f['identified'], array_keys($data))
			)
			{
				throw new Exception("«" . static::$_name . "». Отсутствует поле «{$f['identified']}» обязательное при вставке.");
			}
		}
		
		/* Внешние ключи */
		foreach (self::$_foreign[static::_table()] as $fk_field)
		{
			if (isset($fk_field['class']))
			{
				/* Определяем значение внешнего ключа и делаем проверку по первичному ключу */
				foreach ($data as $identified => $value)
				{
					if ($identified === $fk_field['identified'])
					{
						if ($value === null)
						{
							break;
						}
						
						call_user_func([$fk_field['class'], "is"], $value);
						static::_meta();
						break;
					}
				}
			}
		}
		
		/* Уникальность */
		$err_unique = [];
		foreach (self::$_unique[static::_table()] as $un_field)
		{
			$un_data = [];
			foreach ($un_field as $identified)
			{
				if (array_key_exists($identified, $data))
				{
					$un_data[$identified] = $data[$identified];
				}
			}
			
			$err_unique = array_merge($err_unique, static::unique($un_data));
		}
		if (!empty($err_unique))
		{
			throw new Exception_Many($err_unique);
		}
		
		/* SQL */
		$values_num = []; $num = 1;
		foreach ($data as $f)
		{
			$values_num[] = $num;
			$num++;
		}
		
		$query = self::_get_sql(self::$_sql['insert'], 
		[
			"field" => '"' . implode('", "', array_keys($data)) . '"',
			"values_num" => '$' . implode(', $', $values_num),
			"primary" => self::$_primary[static::_table()]['identified']
		]);
		
		/* Подготовить значения */
		$fdata = static::_data_info($data);
		$fdata = static::_prepare_values($fdata);
		
		/* Запрос */
		$result = pg_query_params(self::$_db_conn, $query, array_column($fdata, "value"));
		if ($result === false)
		{
			throw new Exception("«" . static::$_name . "». Не удалось вставить данные. " . pg_last_error(self::$_db_conn));
		}
		
		$row = pg_fetch_row($result);
		pg_free_result($result);
		
		/* Делаем выборку по первичному ключу и возвращаем результат */
		return static::get($row[0]);
	}
	
	/**
	 * Обновить данные по первичному ключу
	 * 
	 * @param array $data
	 * @param string $primary
	 * @return array
	 */
	public static function update(array $data, string $primary) : array
	{
		/* Собираем данные */
		static::_meta();
		
		/* Проверка */
		static::check($data);
		$old = static::get($primary);
		
		/* Внешние ключи */
		foreach (self::$_foreign[static::_table()] as $fk_field)
		{
			/* Входит ли поле во внешний ключ */
			if (array_diff($fk_field, array_keys($data)) === $fk_field)
			{
				continue;
			}
			
			/* Если присутствует класс делаем проверку */
			if (isset($fk_field['class']))
			{
				/* Определяем значение внешнего ключа и делаем проверку по первичному ключу */
				foreach ($data as $identified => $value)
				{
					if ($identified === $fk_field['identified'])
					{
						if ($value === null)
						{
							break;
						}
						
						call_user_func([$fk_field['class'], "is"], $value);
						break;
					}
				}
			}
		}
		
		/* Уникальность */
		$err_unique = [];
		foreach (self::$_unique[static::_table()] as $un_field)
		{
			/* Входит ли поле в ключ уникальности */
			if (array_diff($un_field, array_keys($data)) === $un_field)
			{
				continue;
			}
			
			/* Данные */
			$un_data = [];
			foreach ($un_field as $identified)
			{
				if (array_key_exists($identified, $data))
				{
					$un_data[$identified] = $data[$identified];
				}
				else
				{
					$un_data[$identified] = $old[$identified];
				}
			}
			
			/* Проверяем на уникальность */
			$err_unique = array_merge($err_unique, static::unique($un_data, $primary));
		}
		
		if (!empty($err_unique))
		{
			throw new Exception_Many($err_unique);
		}
		
		/* SQL */
		$fdata = static::_data_info($data);

		$field = ""; $num = 1;
		foreach ($fdata as $key => $f)
		{
			if ($key !== 0)
			{
				$field .= ",\n";
			}
			
			$field .= "\t\"{$f['identified']}\" = \${$num}";
			$num++;
		}
		
		$query = self::_get_sql(self::$_sql['update'], 
		[
			"field" => $field,
			"primary" => self::$_primary[static::_table()]['identified'],
			"num" => $num
		]);
		
		/* Подготовим значения */
		$fdata = self::_prepare_values($fdata);
		
		/* Запрос */
		$values = array_column($fdata, "value");
		$values[] = $primary;
		
		$result = pg_query_params(self::$_db_conn, $query, $values);
		if ($result === false)
		{
			throw new Exception("«" . static::$_name . "». Не удалось обновить данные. " . pg_last_error(self::$_db_conn));
		}
		pg_free_result($result);
		
		/* Делаем выборку по первичному ключу и возвращаем результат */
		return static::get($primary);
	}

	/**
	 * Удалить
	 * 
	 * @param string $primary
	 * @return array
	 */
	public static function delete(string $primary) : array
	{
		/* Собираем данные */
		static::_meta();
		
		/* Старые данные */
		$old = self::get($primary);
		
		/* SQL */
		$query = self::_get_sql(self::$_sql['delete'], ["primary" => self::$_primary[static::_table()]['identified']]);
		
		/* Запрос */
		$result = pg_query_params(self::$_db_conn, $query, [$primary]);
		if ($result === false)
		{
			throw new Exception("«" . static::$_name . "». Не удалось удалить данные. " . pg_last_error(self::$_db_conn));
		}
		pg_free_result($result);
		
		/* Возвращаем старые значения */
		return $old;
	}

	/**
	 * Получить SQL для создания таблицы
	 * 
	 * @param boolean $drop_if_exist
	 * @return boolean
	 */
	public static function create(bool $drop_if_exist = false) : bool
	{
		/* Собираем данные */
		static::_meta();
		
		/* Удалить таблицу при наличии */
		$drop_table = "";
		if ($drop_if_exist)
		{
			$drop_table = self::_get_sql(self::$_sql['drop_table']);
		}
		
		/* Счётчики */
		$sequence_create = ""; $sequence_owned = "";
		if (!empty(self::$_seq[static::_table()]))
		{
			foreach (self::$_seq[static::_table()] as $seq)
			{
				$sequence_create .= self::_get_sql(self::$_sql['sequence_create'], 
				[
					"schema" => static::$_schema,
					"identified" => strtolower($seq['identified'])
				]);
				
				if ($seq['owned'] === true)
				{
					$sequence_owned .= self::_get_sql(self::$_sql['sequence_owned'], 
					[
						"schema" => static::$_schema,
						"identified" => strtolower($seq['identified']),
						"field" => $seq['field']
					]);
				}
			}
		}
		
		/* Поля */
		$field = "\t";
		foreach (static::$_field as $key => $f)
		{
			/* Запятая */
			if ($key !== 0)
			{
				$field .= ",\n\t";
			}
			
			$field .= Type::get_sql_create(static::$_schema, static::$_table, $f);
		}
		
		/* PRIMARY */
		$primary = "";
		if (!empty(self::$_primary[static::_table()]))
		{
			$primary = self::_get_sql
			(
				self::$_sql['constraint_primary'], 
				[
					"table" => static::$_table,
					"field" => self::$_primary[static::_table()]['identified']
				]
			);
		}
		
		/* UNIQUE */
		$unique = "";
		if (!empty(self::$_unique[static::_table()]))
		{
			$num = 1;
			foreach (self::$_unique[static::_table()] as $key => $field_un)
			{
				$field_un = static::_data_info($field_un);
								
				/* NULL полей нет */
				if (array_search(true, array_column($field_un, "null")) === false or count($field_un) === 1)
				{
					$unique .= self::_get_sql(self::$_sql['index_unique'], 
					[
						"table_name" => static::$_table,
						"key" => $key,
						"field" => '"' . implode('", "', array_column($field_un, "identified")) . '"'
					]);
				}
				/* Есть NULL поля */
				else
				{
					/* Идентификаторы полей с NULL */
					foreach ($field_un as $f)
					{
						if (isset($f['null']) and $f['null'] === true)
						{
							/* Все поля кроме текушего */
							$field_all_but = array_column($field_un, "identified");
							$key = array_search($f['identified'], $field_all_but);
							unset($field_all_but[$key]);
							
							$unique .= self::_get_sql(self::$_sql['index_unique_null'], 
							[
								"table_name" => static::$_table,
								"key" => "UN" . $num,
								"field" => $f['identified'],
								"field_all" => '"' . implode('", "', array_column($field_un, "identified")) . '"',
								"field_all_but" => '"' . implode('", "', $field_all_but) . '"',
							]);
						}
					}
					
				}
				
				$num++;
			}
		}
		
		/* Foreign */
		$foreign = "";
		foreach (self::$_foreign[static::_table()] as $v)
		{
			switch ($v['type'])
			{
				case "restrict":	$fk_type = "ON DELETE RESTRICT";	break;
				case "cascade":		$fk_type = "ON DELETE CASCADE";		break;
				case "null":		$fk_type = "ON DELETE SET NULL";	break;
			}
			
			$foreign .= self::_get_sql(self::$_sql['constraint_foreign'], 
			[
				"key" => $v['key'],
				"identified" => $v['identified'],
				"fk_table" => '"' . $v['schema'] . '"."' . $v['table'] . '"',
				"fk_field" => $v['field'],
				"fk_type" => $fk_type
			]);
		}
		
		/* Другие ограничения */
		$constraint = "";
		foreach (self::$_sql_constraint[static::_table()] as $val)
		{
			$constraint .= ",\n\t" . $val;
		}
		
		/* COMMENT TABLE */
		$comment_table = "";
		if (!empty(static::$_name))
		{
			$comment_table .= self::_get_sql(self::$_sql['comment_table'], ['name' => pg_escape_string(static::$_name)]);
		}
		
		/* COMMENT COLUMN */
		$comment_column = "";
		foreach (static::$_field as $f)
		{
			if (!empty($f['name']))
			{
				$comment_column .= self::_get_sql(self::$_sql['comment_column'], 
				[
					"identified" => $f['identified'],
					"name" => pg_escape_string($f['name'])
				]);
			}
		}
		
		/* Формируем общий SQL */
		$query = self::_get_sql(self::$_sql['create'], 
		[
			"drop_table" => $drop_table,
			"sequence_create" => $sequence_create,
			"sequence_owned" => $sequence_owned,
			"field" => $field,
			"primary" => $primary,
			"unique" => $unique,
			"foreign" => $foreign,
			"constraint" => $constraint,
			"comment_table" => $comment_table,
			"comment_column" => $comment_column
		]);
		
		/* Запрос */
		$result = pg_query(self::$_db_conn, $query);
		if ($result === false)
		{
			return false;
		}
		
		pg_free_result($result);
		
		return true;
	}
	
	/**
	 * Сортировка 
	 * 
	 * @param string $primary
	 * @param string $order
	 */
	public static function order(string $primary, string $order)
	{
		/* Собираем данные */
		static::_meta();
		
		/* Проверка */
		static::is($primary);
		if (!is_numeric($order) and !in_array($order, ["up","down"]))
		{
			throw new Exception("«" . static::$_name . "». Невозможно выполнить сортировку. Необходимо указать «up» или «down».");
		}
		
		/* Находим поле с типом «order» и поле первичный ключ */
		$field_primary = null; $field_order = null; 
		foreach (static::$_field as $f)
		{
			if (isset($f['primary']) and $f['primary'] === true)
			{
				$field_primary = $f;
				continue;
			}
			
			if ($f['type'] === "order")
			{
				$field_order = $f;
				continue;
			}
		}
		
		if (empty($field_order))
		{
			throw new Exception("«" . static::$_name . "». Невозможно выполнить сортировку. Отсутствует поле с типом «order».");
		}
		
		/* WHERE */
		$where = [];
		if (isset($field_order['order_where']))
		{
			$filter = (array)$field_order['order_where'];
			
			$old = static::get($primary);
			foreach ($filter as $identified)
			{
				$where[$identified] = $old[$identified];
			}
		}
		
		/* Выборка */
		$other = static::select($where, [$field_primary['identified'], $field_order['identified']], 0, 0, [$field_order['identified'] => "asc"]);
		if (count($other) < 2)
		{
			throw new Exception("«" . static::$_name . "». Невозможно выполнить сортировку. Необходимо хотя бы два элемента.");
		}
		
		/* Определяем новые значения order */
		foreach ($other as $key => $val)
		{
			if ((string)$val[$field_primary['identified']] === $primary)
			{
				break;
			}
		}
		
		if ($order === "up")
		{
			if ($key === 0)
			{
				throw new Exception("«" . static::$_name . "». Невозможно выполнить сортировку. Выше некуда.");
			}

			$primary_next = $other[$key - 1][$field_primary['identified']];
			$order_int = $other[$key - 1][$field_order['identified']];
			$order_int_next = $other[$key][$field_order['identified']];
		}
		elseif ($order === "down")
		{
			if ($key === count($other) - 1)
			{
				throw new Exception("«" . static::$_name . "». Невозможно выполнить сортировку. Ниже некуда.");
			}

			$primary_next = $other[$key + 1][$field_primary['identified']];
			$order_int = $other[$key + 1][$field_order['identified']];
			$order_int_next = $other[$key][$field_order['identified']];
		}

		/* Обновляем */
		static::update([$field_order['identified'] => $order_int], $primary);
		static::update([$field_order['identified'] => $order_int_next], $primary_next);
	}

	/**
	 * Назначить ресурс подключения к БД
	 * 
	 * @param resource $resource
	 */
	public static function set_db_conn($resource)
	{
		self::$_db_conn = $resource;
	}

	/**
	 * Проверка структуры таблицы
	 */
	public static function check_struct()
	{
		/* Кол-во полей с типом «id» и «order» */
		$type_id_count = 0;
		$type_order_count = 0;
		
		foreach (static::$_field as $f)
		{
			/* Идентификатор */
			if (empty($f['identified']))
			{
				throw new Exception("Таблица «" . static::_table() . "». Не указан идентификатор у поля.");
			}

			if (!Type::check("identified", $f['identified']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Идентификатор задан неверно. " . Type::get_last_error());
			}

			/* Наименование поля */
			if (empty($f['name']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Наименование не задано.");
			}

			if (!Type::check("string", $f['name']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Наименование задано неверно. " . Type::get_last_error());
			}

			/* Тип */
			if (empty($f['type']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Не указан тип.");
			}
			if (!Type::is($f['type']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Отсутствует тип столбца «{$f['type']}».");
			}

			/* NULL */
			if (isset($f['null']) and !is_bool($f['null']))
			{	
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». NULL задан неверно. Необходимо указать «true» или «false».");
			}
			
			/* Enum */
			if ($f['type'] === "enum")
			{
				if (empty($f['enum_values']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Не задан «enum_values»");
				}
				
				if (!is_array($f['enum_values']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «enum_values» не является массивом.");
				}
				
				if (array_values($f['enum_values']) !== $f['enum_values'])
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «enum_values» не является списком.");
				}
			}
			
			/* REQUIRE */
			if (isset($f['require']) and !is_bool($f['require']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «require» задан неверно. Необходиом указать «true» или «false».");
			}
			
			/* EMPTY_ALLOW */
			if (isset($f['empty_allow']) and !is_bool($f['empty_allow']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «empty_allow» задан неверно. Необходиом указать «true» или «false».");
			}

			/* PRIMARY */
			if (isset($f['primary']) and !is_bool($f['primary']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». PRIMARY задан неверно. Необходиом указать «true» или «false».");
			}

			/* ORDER */
			if (isset($f['order']))
			{
				if (!is_string($f['order']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». ORDER задан неверно. Необходимо указать «asc» или «desc».");
				}

				$f['order'] = strtolower($f['order']);

				if ($f['order'] !== "asc" and $f['order'] !== "desc")
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». ORDER задан неверно. Необходимо указать «asc» или «desc».");
				}
			}
			
			/* UNIQUE */
			if (isset($f['unique']) and !is_bool($f['unique']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». UNIQUE задан неверно. Необходиом указать «true» или «false».");
			}

			if (isset($f['unique']) and $f['unique'] === true)
			{
				if (isset($f['unique_key']))
				{
					if (is_scalar($f['unique_key']))
					{
						$f['unique_key'] = (array)$f['unique_key'];
					}

					foreach ($f['unique_key'] as $un)
					{
						if (!Type::check("identified", $un))
						{
							throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». UNIQUE KEY задан неверно. " . Type::get_last_error());
						}
					}
				}
			}

			/* FOREIGN */
			if (!empty($f['foreign']))
			{
				$foreign = $f['foreign'];
				if (empty($foreign['table']) or empty($foreign['field']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Не указан параметр «table» или «field».");
				}
				
				if (!Type::check("identified", $foreign['table']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Параметр «table» задан неверно. " . Type::get_last_error());
				}
				
				if (!Type::check("identified", $foreign['field']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Параметр «field» задан неверно. " . Type::get_last_error());
				}
				
				if (isset($foreign['key']) and !Type::check("identified", $foreign['key']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Параметр «key» задан неверно. " . Type::get_last_error());
				}
				
				if (isset($foreign['class']))
				{
					if (!Type::check("identified", $foreign['class']))
					{
						throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Параметр «class» задан неверно. " . Type::get_last_error());
					}
					
					if (!class_exists($foreign['class']))
					{
						throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Класс «{$foreign['class']}» отсутствует.");
					}
					
					/* Только поле - первичный ключ может быть внешним ключом */
					$foreign_primary = call_user_func([$foreign['class'], "get_meta"], "primary");
					
					if ($foreign['field'] !== $foreign_primary['identified'])
					{
						throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Поле «{$foreign['table']}.{$foreign['field']}» не является первичным ключом.");
					}
				}
				
				if (isset($foreign['type']))
				{
					if (!in_array($foreign['type'], ['restrict', 'cascade', 'null']))
					{
						throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». Foreign задан неверно. Допустимый тип: «restrict» , «cascade», «null».");
					}
				}
			}
	
			/* SQL */
			if 
			(
				isset($f['sql_select']) and !is_string($f['sql_select']) or
				isset($f['sql_create']) and !is_string($f['sql_create']) or
				isset($f['sql_constraint']) and !is_string($f['sql_constraint']) or
				isset($f['sql_type']) and !is_string($f['sql_type']) or
				isset($f['sql_default']) and !is_string($f['sql_default']) 
			)
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». SQL команды заданы неверно.");
			}
			
			/* prepare. Функция обработки значения перед запросом */
			if (isset($f['prepare']))
			{
				if (!is_callable($f['prepare']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «prepare» не является ссылкой на функцию.");
				}
			}
			
			/* equal. Оператор равенства для SQL-команд */
			if (isset($f['equal']) and !in_array($f['equal'], ["=","like","ilike"]))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «equal» задан неверно. Допускаются значения «=», «like», «ilike».");
			}
			
			/* seq, seq_owned. Счётчики */
			if (isset($f['seq']))
			{
				if (!Type::check("identified", $f['seq']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «seq» задан неверно. " . Type::get_last_error());
				}
				
				if (isset($f['seq_type']) and !in_array($f['seq_type'], ["next","current"]))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «seq_type» задан неверно. Необходиом указать «next» или «current».");
				}
					
				if (isset($f['seq_owned']) and !is_bool($f['seq_owned']))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «seq_owned» задан неверно. Необходиом указать «true» или «false».");
				}
			}
			
			/* PHP-тип */
			if (isset($f['php_type']))
			{
				$php_type = ["int", "integer", "bool", "boolean", "float", "double", "real", "string"];
				if (!in_array($f['php_type'], $php_type))
				{
					throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «php_type» задан неверно. Допустимые значения: " . implode(", ", $php_type) . ".");
				}
			}
			
			/* Легкие поля */
			if (isset($f['lite']) and !is_bool($f['lite']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «lite» задан неверно. Необходиом указать «true» или «false».");
			}
			
			/* Делать ли проверку */
			if (isset($f['check']) and !is_bool($f['check']))
			{
				throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «check» задан неверно. Необходиом указать «true» или «false».");
			}
			
			/* Type «id» */
			if ($f['type'] === "id")
			{
				if ($type_id_count > 0)
				{
					throw new Exception("Таблица «" . static::_table() . "». Поле с типом «id» должно быть только одно.");
				}
				
				$type_id_count++;
			}
			
			/* Type «order» */
			if ($f['type'] === "order")
			{
				if (isset($f['order_where']))
				{
					$f['order_where'] = (array)$f['order_where'];
					foreach ($f['order_where'] as $identified)
					{
						if (!in_array($identified, array_column(static::$_field, "identified")))
						{
							throw new Exception("Поле «" . static::_table() . ".{$f['identified']}». «order_where» задан неверно. Отсутствует поле «{$identified}».");
						}
					}
				}
				
				if ($type_order_count > 0)
				{
					throw new Exception("Таблица «" . static::_table() . "». Поле с типом «order» должно быть только одно.");
				}
				
				$type_order_count++;
			}
		}
		
		/* Получаем meta и проверяем DEFAULT значения */
		static::_meta();
		foreach (static::$_field as $f)
		{
			if (array_key_exists("default", $f))
			{
				self::check([$f['identified'] => $f['default']], false);
			}
		}
	}
	
	/**
	 * Получить мета данные по таблице
	 * 
	 * @param string $meta
	 * @return array
	 */
	public static function get_meta($meta) : array
	{
		static::_meta();
		
		if (!in_array($meta, ["field", "primary", "order", "unique", "foreign", "seq"]))
		{
			throw new Exception("Таблица «" . static::_table() . "». Метаданные типа «{$meta}» отсутствуют.");
		}
		
		if ($meta === "field")
		{
			return static::$_field;
		}
		else
		{
			$meta = "_" . $meta;
			return self::$$meta[static::_table()];
		}
	}

	/**
	 * Получить сведения по полям на основании данных
	 * 
	 * @param array $data
	 * @return array
	 */
	private static function _data_info(array $data) : array
	{
		$fdata = [];
		
		/* Массив-список - список идентификаторов */
		if ($data === array_values($data))
		{
			foreach ($data as $identified)
			{
				$isset = false;
				foreach (static::$_field as $f)
				{
					if ($identified === $f['identified'])
					{
						$fdata[] = $f;
						$isset = true;
						break;
					}
				}

				if ($isset === false)
				{
					throw new Exception("«" . static::$_name . "». Поля с идентификатором «{$identified}» не существует.");
				}
			}
		}
		/* Ассоциативный массив */
		else
		{
			foreach ($data as $identified => $value)
			{
				$isset = false;
				foreach (static::$_field as $f)
				{
					if ($identified === $f['identified'])
					{
						$f = array_merge($f, ["value" => $value]);
						$fdata[] = $f;
						$isset = true;
						break;
					}
				}

				if ($isset === false)
				{
					throw new Exception("«" . static::$_name . "». Поля с идентификатором «{$identified}» не существует.");
				}
			}
		}
		
		return $fdata;
	}

	/**
	 * Собираем информацию по таблице
	 */
	private static function _meta() : bool
	{
		/* Не собирать повторно */
		if (in_array(static::_table(), self::$_meta))
		{
			return true;
		}
		
		/* Получаем объект соединения */
		self::_connect();
		
		/* Создаём ключи по нашей таблице */
		self::$_primary[static::_table()] = [];
		self::$_order[static::_table()] = [];
		self::$_unique[static::_table()] = [];
		self::$_foreign[static::_table()] = [];
		self::$_seq[static::_table()] = [];
		self::$_sql_constraint[static::_table()] = [];
		
		/* Совмещаем данные полей с полями типа */
		foreach (static::$_field as $key => $f)
		{
			$type = Type::get($f['type']);
			static::$_field[$key] = array_merge($type, $f);
		}
		
		/* Собираем сведения */
		$un_num = 1;
		foreach (static::$_field as $key => $f)
		{
			/* Primary */
			if (isset($f['primary']) and $f['primary'] === true)
			{	
				if (!empty(self::$_primary[static::_table()]))
				{
					throw new Exception("Таблица «" . static::_table() . "». Два первичных ключа.");
				}
				
				self::$_primary[static::_table()] = 
				[
					"identified" => $f['identified'],
					"name" => $f['name'],
					"type" => $f['type']
				];
			}
			
			/* Order */
			if (isset($f['order']))
			{
				self::$_order[static::_table()][$f['identified']] = strtolower($f['order']);
			}
			
			/* Unique */
			if (!empty($f['unique_key']))
			{
				$f['unique'] = true;
			}
			
			if (isset($f['unique']) and $f['unique'] === true)
			{
				if (!isset($f['unique_key']))
				{
					$unique_key = ["UN_" . $un_num];
					$un_num++;
				}
				else
				{
					if (is_scalar($f['unique_key']))
					{
						$unique_key = [$f['unique_key']];
					}
					else
					{
						$unique_key = $f['unique_key'];
					}
				}
				
				foreach ($unique_key as $un)
				{
					self::$_unique[static::_table()][$un][] = $f['identified'];
				}
			}
			
			/* Foreign */
			if (isset($f['foreign']))
			{
				if (empty($f['foreign']['key']))
				{
					$f['foreign']['key'] = static::$_table . "_FK_" . $f['identified'];
				}
				
				$f['foreign']['identified'] = $f['identified'];
				
				if (empty($f['foreign']['schema']))
				{
					$f['foreign']['schema'] = static::$_schema;
				}
				
				if (empty($f['foreign']['type']))
				{
					$f['foreign']['type'] = "cascade";
				}
				
				self::$_foreign[static::_table()][] = $f['foreign'];
			}
			
			/* SEQUNECE */
			if (isset($f['seq']))
			{
				$seq_identified = self::_get_sql($f['seq'], 
				[
					"table" => static::$_table,
					"identified" => $f['identified']
				]);
				
				$seq_owned = false;
				if (isset($f['seq_owned']) and $f['seq_owned'] === true)
				{
					$seq_owned = true;
				}
				
				/* Добавить только один раз */
				$seq_key = false;
				if (!empty(self::$_seq[static::_table()]))
				{
					$seq_key = array_search($seq_identified, array_column(self::$_seq[static::_table()], "identified"));
				}
				
				if ($seq_key === false)
				{
					$seq = 
					[
						"identified" => $seq_identified,
						"owned" => $seq_owned
					];
					
					if ($seq_owned === true)
					{
						$seq['field'] = $f['identified'];
					}
					
					self::$_seq[static::_table()][] = $seq;
				}
				elseif ($seq_key !== false and $seq_owned === true)
				{
					self::$_seq[static::_table()][$seq_key]['owned'] = true;
				}
			}
			
			/* default, require, sql_default */
			if (array_key_exists("default", $f) or isset($f["sql_default"]))
			{
				static::$_field[$key]['require'] = false;
			}
			
			/* Ограничения */
			if (isset($f['sql_constraint']))
			{
				$replace = 
				[
					"schema" => static::$_schema,
					"table"	=> static::$_table,
					"identified" => $f['identified']
				];
				
				if (isset($f['enum_values']))
				{
					$replace['enum_values'] = "'" . implode("', '", $f['enum_values']) . "'";
				}
				
				self::$_sql_constraint[static::_table()][] = self::_get_sql($f['sql_constraint'], $replace);
			}
		}
		
		/* Ещё проверки */
		if (empty(self::$_primary[static::_table()]))
		{
			throw new Exception("Таблица «" . static::_table() . "». Не задан первичный ключ.");
		}
		
		/* Укажим таблицу у которой собрана meta */
		self::$_meta[] = static::_table();
		
		return true;
	}
	
	/**
	 * Получить SQL на основании шаблона
	 * 
	 * @param string $sql
	 * @param array $data
	 * @return string
	 */
	private static function _get_sql(string $sql, array $data = []) : string
	{
		/* Данные по умолчанию */
		if (!isset($data['table']))
		{
			$data['table'] = static::_table(true);
		}
		
		/* Ключи в фигурные скобки */
		foreach ($data as $key => $value)
		{
			$data['{' . $key . '}'] = $value;
			unset($data[$key]);
		}
		
		/* Замена */
		$sql = strtr($sql, $data);
		
		return $sql;
	}
	
	/**
	 * Подготовить значения
	 * 
	 * @param array $fdata
	 * @return array
	 */
	private static function _prepare_values(array $fdata) : array
	{
		foreach ($fdata as $key => $f)
		{
			if (!empty($f['prepare']) and !empty($f['value']))
			{
				$fdata[$key]['value'] = Type::prepare($f['value'], $f['prepare']);
			}
		}
		
		return $fdata;
	}
	
	/**
	 * Привести строку к PHP типу
	 * 
	 * @param array $row
	 */
	private static function _convert_row_php_type(array &$row)
	{
		foreach ($row as $key => $value)
		{
			list($identified, $php_type) = explode("|", $key);

			if ($value !== null)
			{
				switch ($php_type)
				{
					case "int":
					case "integer":
					{
						$row[$identified] = (int)$value;
					}
					break;

					case "bool":
					case "boolean":
					{
						$row[$identified] = (bool)$value;
					}
					break;

					case "float":
					case "double":
					case "real":
					{
						$row[$identified] = (float)$value;
					}
					break;
				
					default :
					{
						$row[$identified] = $value;
					}
				}
			}
			else
			{
				$row[$identified] = $value;
			}

			unset($row[$key]);
		}
	}
	
	/**
	 * Получить имя таблицы
	 * 
	 * @param boolean $quotes
	 * @return string
	 */
	private static function _table(bool $quotes = false) : string
	{
		if ($quotes === false)
		{
			return static::$_schema . "." . static::$_table;
		}
		elseif ($quotes === true)
		{
			return '"' . static::$_schema . '"."' . static::$_table . '"';
		}
	}
	
	/**
	 * Получить объект соединения
	 */
	private static function _connect()
	{
		if (empty(self::$_db_conn))
		{
			self::$_db_conn = G::db()->get_db_conn();
		}
	}
}

/**
 * Исключение включающее список ошибок
 */
class Exception_Many extends Exception
{
	/**
	 * Список 
	 * 
	 * @var array
	 */
	private $_err = [];

	/**
	 * Конструктор
	 */
	public function __construct(array $err)
	{
		$this->_err = $err;
		
		parent::__construct(null);
	}
	
	/**
	 * Получить список ошибок
	 */
	public function get_err()
	{
		return $this->_err;
	}
}
?>