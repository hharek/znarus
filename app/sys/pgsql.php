<?php

/**
 * Класс для работы с PostgreSQL
 * 
 * @category	Databases
 * @author		Sergeev Denis <hharek@yandex.ru>
 * @copyright	2011 Sergeev Denis
 * @license		https://github.com/hharek/zn_pgsql/wiki/MIT-License MIT License
 * @version		0.2.1
 * @link		https://github.com/hharek/zn_pgsql/
 */
class ZN_Pgsql
{

	/**
	 * Дескриптор подключения
	 * 
	 * @var resource
	 */
	private $_db_conn;

	/**
	 * Хост
	 * 
	 * @var string
	 */
	private $_host;

	/**
	 * Пользователь
	 * 
	 * @var string
	 */
	private $_user;

	/**
	 * Пароль
	 * 
	 * @var string
	 */
	private $_pass;

	/**
	 * Наименование БД
	 * 
	 * @var string
	 */
	private $_db_name;

	/**
	 * Порт
	 * 
	 * @var int
	 */
	private $_port;

	/**
	 * Постоянное соединение
	 * 
	 * @var bool
	 */
	private $_persistent;

	/**
	 * Поддержка SSL (disable | prefer | require)
	 * 
	 * @var string
	 */
	private $_ssl;

	/**
	 * Наименование схемы
	 * 
	 * @var string
	 */
	private $_schema = "public";
	
	/**
	 * Текущая схема в search_path
	 * 
	 * @var string
	 */
	private $_schema_current = "public";

	/**
	 * Использовать кэширование
	 * 
	 * @var bool
	 */
	private $_cache = false;

	/**
	 * Папка с кэшом
	 * 
	 * @var string
	 */
	private $_cache_dir = null;

	/**
	 * Конструктор
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $db_name
	 * @param string $schema
	 * @param string $cache_dir
	 * @param int $port
	 * @param bool $persistent
	 * @param string $ssl (disable|prefer|require)
	 * @return bool
	 */
	public function __construct($host, $user, $pass, $db_name, $schema="public", $cache_dir=null, $port=5432, $persistent=false, $ssl="disable")
	{
		/* Проверка */
		if (empty($host))
		{
			throw new Exception("Хост не задан.", 11);
		}

		if (empty($user))
		{
			throw new Exception("Пользователь не задан.", 12);
		}

		if (empty($db_name))
		{
			throw new Exception("Наименование базы не задано.", 13);
		}

		if (empty($schema))
		{
			throw new Exception("Схема задана неверно.", 14);
		}

		$port = (int) $port;
		if ($port == 0)
		{
			throw new Exception("Порт задан неверно.", 15);
		}

		$persistent = (bool) $persistent;

		if (!in_array($ssl, array('disable', 'prefer', 'require')))
		{
			throw new Exception("Тип подключения по SSL задан неверно.", 16);
		}

		/* Назначить */
		$this->_host = $host;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_db_name = $db_name;
		$this->_schema = $schema;
		$this->_port = $port;
		$this->_persistent = $persistent;
		$this->_ssl = $ssl;

		/* Создание файлов и папок для работы кэша */
		if (!empty($cache_dir))
		{
			$this->_cache_dir_create($cache_dir);
			$this->_cache_dir = $cache_dir;
			$this->_cache = true;
		}

		/* Возможность клонировать не создавая нового соединения */
		$this->_db_conn = &$this->_db_conn;
		
		/* При клонировании сохранять обозначение текущей схемы */
		$this->_schema_current = &$this->_schema_current;
		

		return true;
	}

	/**
	 * Деструктор
	 * 
	 * @return bool
	 */
	public function __destruct()
	{
		$this->close();

		return true;
	}

	/**
	 * Соединение
	 * 
	 * @return bool
	 */
	public function connect()
	{
		if (!$this->is_connect())
		{
			$str_connect = "host={$this->_host} user={$this->_user} password={$this->_pass} dbname={$this->_db_name} port={$this->_port} sslmode={$this->_ssl}";

			if ($this->_persistent)
			{
				$this->_db_conn = @pg_pconnect($str_connect);
			}
			else
			{
				$this->_db_conn = @pg_connect($str_connect);
			}

			if (!$this->_db_conn)
			{
				$error = error_get_last();
				throw new Exception("Не удалось установить соединение. " . $error['message'], 21);
			}

			/* Схема по умолчанию */
			$query = "SET search_path TO '" . $this->escape($this->_schema) . "'";
			$result = @pg_query($this->_db_conn, $query);
			if ($result === false)
			{
				throw new Exception("Схема указана неверно. ".pg_last_error($this->_db_conn), 22);
			}
			pg_free_result($result);
		}

		return true;
	}

	/**
	 * Закрыть соединение
	 * 
	 * @return bool
	 */
	public function close()
	{
		if ($this->is_connect())
		{
			@pg_close($this->_db_conn);
		}

		return true;
	}

	/**
	 * Пересоединение
	 * 
	 * @return bool
	 */
	public function reconnect()
	{
		$this->connect();

		if (!pg_connection_reset($this->_db_conn))
		{
			throw new Exception("Пересоединение не удалось.", 31);
		}

		return true;
	}

	/**
	 * Проверить соединение
	 * 
	 * @return bool
	 */
	public function is_connect()
	{
		if (!is_resource($this->_db_conn))
		{
			return false;
		}
		else
		{
			$status = pg_connection_status($this->_db_conn);
			if ($status === PGSQL_CONNECTION_OK)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * Назначить схему
	 * 
	 * @param string $schema 
	 * @return bool
	 */
	public function set_schema($schema)
	{
		$schema = (string) $schema;
		$schema = trim($schema);

		if (empty($schema))
		{
			throw new Exception("Схема не задана.", 41);
		}
		
		$this->_schema = $schema;

		return true;
	}
	
	

	/**
	 * Вернуть схему
	 * 
	 * @return string
	 */
	public function get_schema()
	{
		return $this->_schema;
	}

	/**
	 * Активация кэширования
	 * 
	 * @param string $dir
	 * @return bool
	 */
	public function cache_enable($dir=null)
	{
		if (!is_null($dir))
		{
			$this->_cache_dir_create($dir);
			$this->_cache_dir = $dir;
		}
		else
		{
			if(empty ($this->_cache_dir))
			{
				throw new Exception("Не указана папка для кэширования.", 51);
			}
			
			if (!is_dir($this->_cache_dir))
			{
				throw new Exception("Папка \"{$this->_cache_dir}\" для кэширования указана неверно.", 52);
			}
		}

		$this->_cache = true;

		return true;
	}

	/**
	 * Отключить кэширование
	 * 
	 * @return bool
	 */
	public function cache_disable()
	{
		$this->_cache = false;

		return true;
	}
	
	/**
	 * Удалить весь кэш
	 * 
	 * @return bool
	 */
	public function cache_truncate()
	{
		$dirs = array($this->_cache_dir . "/query", $this->_cache_dir . "/table");

		foreach ($dirs as $dval)
		{
			if (is_dir($dval))
			{
				$files = scandir($dval);
				if (!empty($files))
				{
					foreach ($files as $fval)
					{
						if (is_file($dval . "/" . $fval) and $fval != ".." and $fval != ".")
						{
							unlink($dval . "/" . $fval);
						}
					}
				}
			}
		}

		return true;
	}

	/**
	 * Выполнить запрос
	 * 
	 * @param string $query
	 * @param array $param
	 * @param array $tables
	 * @param bool $is_modify
	 * @return bool
	 */
	public function query($query, $param=null, $tables=null)
	{
		/* Проверка */
		$this->_check_query($query);
		if (!$this->_is_single_query($query))
		{
			throw new Exception("Множественный запрос. " . func_get_arg(0), 61);
		}

		if (!empty($param))
		{
			$query = $this->_get_query_param($query, $param);
		}

		if (!empty($tables))
		{
			if (!is_array($tables) and !is_string($tables))
			{
				throw new Exception("Таблицы заданы неверно.", 62);
			}

			if (is_string($tables))
			{
				$tables = array($tables);
			}
		}

		/* Запрос */
		$this->connect();
		$this->_query_schema();
		
		$result = @pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 63);
		}

		pg_free_result($result);

		/* Удалить кэширование */
		if ($this->_cache == true and !empty($tables))
		{
			foreach ($tables as $val)
			{
				$this->_cache_table_delete($val);
			}
		}

		return true;
	}

	/**
	 * Выполнить запрос и вернуть ассоциативный массив
	 * 
	 * @param string $query
	 * @param array $param
	 * @param array $tables
	 * @param bool $is_modify
	 * @param string $cache_time
	 * @return array
	 */
	public function query_assoc($query, $param=null, $tables=null, $is_modify=false, $cache_time="+1 month")
	{
		/* Проверка */
		$this->_check_query($query);
		if (!$this->_is_single_query($query))
		{
			throw new Exception("Множественный запрос. " . func_get_arg(0), 71);
		}

		if (!empty($param))
		{
			$query = $this->_get_query_param($query, $param);
		}

		if (!empty($tables))
		{
			if (!is_array($tables) and !is_string($tables))
			{
				throw new Exception("Таблицы заданы неверно.", 72);
			}

			if (is_string($tables))
			{
				$tables = array($tables);
			}
		}

		$is_modify = (boolean) $is_modify;
		
		$cache_time = trim($cache_time);
		
		/* Берём данные из кэша */
		if ($this->_cache == true and $is_modify == false and is_file($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "assoc")))
		{
			$cache_result = unserialize(file_get_contents($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "assoc")));
			if(time() <= $cache_result['time'])
			{
				return $cache_result['result'];
			}
			else
			{
				/* Удаляем старый кэш */
				unlink($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "assoc"));
			}
		}

		/* Удаляем кэш */
		if ($this->_cache == true and $is_modify == true)
		{
			foreach ($tables as $val)
			{
				$this->_cache_table_delete($val);
			}
		}

		/* Запрос */
		$this->connect();
		$this->_query_schema();

		$result = @pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 74);
		}

		/* Результат запроса */
		$result_ar = array();
		while ($row = pg_fetch_assoc($result))
		{
			$result_ar[] = $row;
		}
		pg_free_result($result);

		/* Создаём кэш */
		if ($this->_cache == true and $is_modify == false and !empty ($tables))
		{
			$this->_cache_create($query, $tables, $result_ar, "assoc", $cache_time);
		}

		return $result_ar;
	}

	/**
	 * Выполнить запрос и вернуть первый столбец в обычный массив
	 * 
	 * @param string $query
	 * @param array $param
	 * @param array $tables
	 * @param bool $is_modify
	 * @param string $cache_time
	 * @return array 
	 */
	public function query_column($query, $param=null, $tables=null, $is_modify=false, $cache_time="+1 month")
	{
		/* Проверка */
		$this->_check_query($query);
		if (!$this->_is_single_query($query))
		{
			throw new Exception("Множественный запрос. " . func_get_arg(0), 81);
		}

		if (!empty($param))
		{
			$query = $this->_get_query_param($query, $param);
		}

		if (!empty($tables))
		{
			if (!is_array($tables) and !is_string($tables))
			{
				throw new Exception("Таблицы заданы неверно.", 82);
			}

			if (is_string($tables))
			{
				$tables = array($tables);
			}
		}

		$is_modify = (boolean) $is_modify;
		
		$cache_time = trim($cache_time);

		/* Берём данные из кэша */
		if ($this->_cache == true and $is_modify == false and is_file($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "column")))
		{
			$cache_result = unserialize(file_get_contents($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "column")));
			if(time() <= $cache_result['time'])
			{
				return $cache_result['result'];
			}
			else
			{
				/* Удаляем старый кэш */
				unlink($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "column"));
			}
		}

		/* Удаляем кэш */
		if ($this->_cache == true and $is_modify == true)
		{
			foreach ($tables as $val)
			{
				$this->_cache_table_delete($val);
			}
		}

		/* Запрос */
		$this->connect();
		$this->_query_schema();

		$result = @pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 83);
		}

		/* Результат запроса */
		$result_ar = array();
		while ($row = pg_fetch_row($result))
		{
			$result_ar[] = $row[0];
		}
		pg_free_result($result);

		/* Создаём кэш */
		if ($this->_cache == true and $is_modify == false and !empty ($tables))
		{
			$this->_cache_create($query, $tables, $result_ar, "column", $cache_time);
		}

		return $result_ar;
	}

	/**
	 * Выполнить запрос и вернуть первую строку в ассоциативном массиве
	 * где индекс наименование столбца
	 * 
	 * @param string $query
	 * @param array $param
	 * @param array $tables
	 * @param bool $is_modify
	 * @param string $cache_time
	 * @return array
	 */
	public function query_line($query, $param=null, $tables=null, $is_modify=false, $cache_time="+1 month")
	{
		/* Проверка */
		$this->_check_query($query);
		if (!$this->_is_single_query($query))
		{
			throw new Exception("Множественный запрос. " . func_get_arg(0), 91);
		}

		if (!empty($param))
		{
			$query = $this->_get_query_param($query, $param);
		}

		if (!empty($tables))
		{
			if (!is_array($tables) and !is_string($tables))
			{
				throw new Exception("Таблицы заданы неверно.", 92);
			}

			if (is_string($tables))
			{
				$tables = array($tables);
			}
		}

		$is_modify = (boolean) $is_modify;
		
		$cache_time = trim($cache_time);

		/* Берём данные из кэша */
		if ($this->_cache == true and $is_modify == false and is_file($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "line")))
		{
			$cache_result = unserialize(file_get_contents($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "line")));
			if(time() <= $cache_result['time'])
			{
				return $cache_result['result'];
			}
			else
			{
				/* Удаляем старый кэш */
				unlink($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "line"));
			}
		}

		/* Удаляем кэш */
		if ($this->_cache == true and $is_modify == true)
		{
			foreach ($tables as $val)
			{
				$this->_cache_table_delete($val);
			}
		}

		/* Запрос */
		$this->connect();
		$this->_query_schema();

		$result = @pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 93);
		}

		/* Результат запроса */
		$line = pg_fetch_assoc($result);
		pg_free_result($result);

		/* Создаём кэш */
		if ($this->_cache == true and $is_modify == false and !empty ($tables))
		{
			$this->_cache_create($query, $tables, $line, "line", $cache_time);
		}

		return $line;
	}

	/**
	 * Выполнить запрос и вернуть первый столбец в первой строке
	 * 
	 * @param string $query
	 * @param array $param
	 * @param array $tables
	 * @param bool $is_modify
	 * @param string $cache_time
	 * @return string
	 */
	public function query_one($query, $param=null, $tables=null, $is_modify=false, $cache_time="+1 month")
	{
		/* Проверка */
		$this->_check_query($query);
		if (!$this->_is_single_query($query))
		{
			throw new Exception("Множественный запрос. " . func_get_arg(0), 102);
		}

		if (!empty($param))
		{
			$query = $this->_get_query_param($query, $param);
		}

		if (!empty($tables))
		{
			if (!is_array($tables) and !is_string($tables))
			{
				throw new Exception("Таблицы заданы неверно.", 103);
			}

			if (is_string($tables))
			{
				$tables = array($tables);
			}
		}

		$is_modify = (boolean) $is_modify;
		
		$cache_time = trim($cache_time);

		/* Берём данные из кэша */
		if ($this->_cache == true and $is_modify == false and is_file($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "one")))
		{
			$cache_result = unserialize(file_get_contents($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "one")));
			if(time() <= $cache_result['time'])
			{
				return $cache_result['result'];
			}
			else
			{
				/* Удаляем старый кэш */
				unlink($this->_cache_dir . "/query/" . $this->_get_cache_file_name_query($query, "one"));
			}
		}

		/* Удаляем кэш */
		if ($this->_cache == true and $is_modify == true)
		{
			foreach ($tables as $val)
			{
				$this->_cache_table_delete($val);
			}
		}

		/* Запрос */
		$this->connect();
		$this->_query_schema();
		
		$result = @pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 104);
		}

		/* Результат запроса */
		$row = pg_fetch_row($result);
		pg_free_result($result);

		if (!isset($row[0]))
		{
			$row[0] = null;
		}

		/* Создаём кэш */
		if ($this->_cache == true and $is_modify == false and !empty ($tables))
		{
			$this->_cache_create($query, $tables, $row[0], "one", $cache_time);
		}

		return $row[0];
	}

	/**
	 * Множественный запрос
	 * 
	 * @param string $query
	 * @param array $param
	 * @param array $tables
	 * @return bool
	 */
	public function multi_query($query, $param=null, $tables=null)
	{
		/* Проверка */
		$this->_check_query($query);

		if (!empty($param))
		{
			$query = $this->_get_query_param($query, $param);
		}

		if (!empty($tables))
		{
			if (!is_array($tables) and !is_string($tables))
			{
				throw new Exception("Таблицы заданы неверно.", 112);
			}

			if (is_string($tables))
			{
				$tables = array($tables);
			}
		}

		/* Запрос */
		$this->connect();
		$this->_query_schema();

		$result = @pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 113);
		}

		pg_free_result($result);

		/* Удалить кэширование */
		if ($this->_cache == true and !empty($tables))
		{
			foreach ($tables as $val)
			{
				$this->_cache_table_delete($val);
			}
		}

		return true;
	}

	/**
	 * Экранировать строку
	 * 
	 * @param string $str
	 * @return string
	 */
	public function escape($str)
	{
		if (!$this->is_connect())
		{
			return pg_escape_string($str);
		}
		else
		{
			return pg_escape_string($this->_db_conn, $str);
		}
	}

	/**
	 * Проверка таблицы на существование
	 * 
	 * @param string $table
	 * @return bool
	 */
	public function is_table($table)
	{
		$table = trim($table);
		if (empty($table))
		{
			throw new Exception("Не задано имя таблицы.", 121);
		}

		$query =
<<<SQL
SELECT COUNT(*) as count
FROM "information_schema"."tables"
WHERE "table_schema" = $1
AND "table_name" = $2
SQL;
		$count = $this->query_one($query, array($this->_schema, $table));
		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Проверка на существование столбца
	 * 
	 * @param type $table
	 * @param type $column
	 * @return bool
	 */
	public function is_column($table, $column)
	{
		$table = trim($table);
		if (empty($table))
		{
			throw new Exception("Не задано имя таблицы.", 131);
		}

		$column = trim($column);
		if (empty($column))
		{
			throw new Exception("Не задано имя столбца.", 132);
		}

		$query =
<<<SQL
SELECT COUNT(*) as count
FROM "information_schema"."columns"
WHERE "table_schema" = $1
AND "table_name" = $2
AND "column_name" = $3
SQL;

		$count = $this->query_one($query, array($this->_schema, $table, $column));
		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	

	/**
	 * Проверить является ли запрос одиночным (UNION не учитывается)
	 * 
	 * @param string $query
	 * @return bool
	 */
	private function _is_single_query($query)
	{
		$apostrof = false;
		$str_count = mb_strlen($query, "UTF-8");

		$single_query = true;
		$backslash_before = false;
		for ($i = 1; $i <= $str_count; $i++)
		{
			$simbol = mb_substr($query, $i - 1, 1, "UTF-8");

			if ($simbol === "'")
			{
				if ($apostrof == true)
				{
					$apostrof = false;
				}
				else
				{
					$apostrof = true;
				}
			}
			elseif ($simbol === ';' and $apostrof === false)
			{
				$single_query = false;
				break;
			}
		}

		return $single_query;
	}

	/**
	 * Выдать и проверить параметризованный запрос
	 * 
	 * @param string $query
	 * @param array $param
	 * @return string
	 */
	private function _get_query_param($query, $param)
	{
		/*** Поиск параметров в запросе ***/
		if (!is_array($param) and !is_scalar($param))
		{
			throw new Exception("Параметры запроса заданы неверно.", 141);
		}
		
		if (is_scalar($param))
		{
			$param = array($param);
		}

		$query .= " ";
		$strlen = mb_strlen($query, "UTF-8");

		$zifrao = false;
		$number_ar = array();
		$number_check = array();
		$number = '';
		$apostrof = false;
		for ($i = 1; $i <= $strlen; $i++)
		{
			$simbol = mb_substr($query, $i - 1, 1, "UTF-8");

			/* num_start */
			if ($simbol === "$")
			{
				$zifrao = true;
			}

			/* num */
			elseif ($zifrao == true and in_array($simbol, array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9')))
			{
				$number .= $simbol;
			}

			/* num_end */
			elseif ($zifrao == true and !in_array($simbol, array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9')))
			{
				$zifrao = false;
				if ($number != '')
				{
					$number_ar[] = array("number" => $number, "apostrof" => $apostrof);
					$number_check[] = $number;
				}
				$number = '';
			}

			/* apostrof */
			if ($simbol === "'")
			{
				if ($apostrof == true)
				{
					$apostrof = false;
				}
				else
				{
					$apostrof = true;
				}
			}
		}

		/*** Проверка заданных параметров ***/
		sort($number_check);
		foreach ($number_check as $key => $val)
		{
			if ($key + 1 != $val)
			{
				throw new Exception("Числовые параметры заданы неверно.", 142);
			}
		}
		
		if(count($number_check) != count($param))
		{
			throw new Exception("Параметры заданы неверно.", 143);
		}

		foreach ($param as $key => $val)
		{
			if ($key + 1 != $number_check[$key])
			{
				throw new Exception("Указаные параметры заданы неверно.", 144);
			}
		}

		/*** Подстановка ***/
		$search = array();
		$replace = array();

		foreach ($number_ar as $key => $val)
		{
			$search[] = '$' . $val['number'];

			if ($val['apostrof'] === true)
			{
				$replace[] = $this->escape($param[$val['number'] - 1]);
			}
			else
			{
				/* Не экранировать NULL */
				if(is_null($param[$val['number'] - 1]))
				{
					$replace[] = "NULL";
				}
				/* Не экранировать булёвое значение */
				elseif(is_bool($param[$val['number'] - 1]))
				{
					if($param[$val['number'] - 1] === true)
					{
						$replace[] = "true";
					}
					else
					{
						$replace[] = "false";
					}
				}
				elseif (is_int($param[$val['number'] - 1]) or is_float($param[$val['number'] - 1])) 
				{
					$replace[] = $param[$val['number'] - 1];
				}
				/* Остальное экранировать */
				else
				{
					$replace[] = "'" . $this->escape($param[$val['number'] - 1]) . "'";
				}
			}
		}

		$query = str_replace($search, $replace, $query);

		return $query;
	}

	/**
	 * Создать кэш запроса
	 * 
	 * @param string $query
	 * @param array $tables
	 * @param mixed $result
	 * @param string $result_type
	 * @param string $time
	 * @return bool
	 */
	private function _cache_create($query, $tables, &$result, $result_type, $time)
	{
		/* Проверка */
		if (empty($query))
		{
			throw new Exception("Запрос задан неверно.", 151);
		}

		if (empty($tables) or !is_array($tables))
		{
			throw new Exception("Не указаны таблицы.", 152);
		}
		
		if(!in_array($result_type, array("assoc","column","line","one")))
		{
			throw new Exception("Тип результата задан неверно.", 153);
		}
		
		$time = strtotime($time);
		if($time === false)
		{
			throw new Exception("Время хранения кэша \"" . func_get_arg(4) . "\" задано неверно.", 154);
		}
		
		/* Общий кэш */
		$file_name_query = $this->_get_cache_file_name_query($query, $result_type);

		if (is_file($this->_cache_dir . "/query/" . $file_name_query))
		{
			unlink($this->_cache_dir . "/query/" . $file_name_query);
		}

		$query_ar['tables'] = $tables;
		$query_ar['result'] = $result;
		$query_ar['time'] = $time;

		file_put_contents($this->_cache_dir . "/query/" . $file_name_query, serialize($query_ar));

		/* Кэш для таблиц */
		foreach ($tables as $key => $val)
		{
			$file_name_table = $this->_cache_dir . "/table/" . $this->_get_cache_file_name_table($val);
			if (is_file($file_name_table))
			{
				$table_query = unserialize(file_get_contents($file_name_table));
			}
			else
			{
				$table_query = array();
			}

			if (!in_array($file_name_query, $table_query))
			{
				$table_query[] = $file_name_query;
			}

			file_put_contents($file_name_table, serialize($table_query));
		}

		return true;
	}

	/**
	 * Удалить кэш у таблицы
	 *  
	 * @param string $tables
	 * @return bool
	 */
	private function _cache_table_delete($table)
	{
		/* Проверка */
		if (empty($table))
		{
			throw new Exception("Не указана таблица.", 161);
		}

		$mt_file_name = $this->_cache_dir . "/table/" . $this->_get_cache_file_name_table($table);
		if (!is_file($mt_file_name))
		{
			return true;
		}

		/* Мои запросы относящиеся к моей таблице */
		$mt_query_ar = unserialize(file_get_contents($mt_file_name));
		if (!empty($mt_query_ar))
		{
			foreach ($mt_query_ar as $mq_val)
			{
				/* Таблицы привязанные к моему запросу */
				$mq_file_name = $this->_cache_dir . "/query/" . $mq_val;
				$d_table_ar = unserialize(file_get_contents($mq_file_name));

				/* Удалить пометку запроса в файле таблицы */
				foreach ($d_table_ar['tables'] as $d_key => $d_val)
				{
					$d_table_file_name = $this->_cache_dir . "/table/" . $this->_get_cache_file_name_table($d_val);
					$d_table_query = unserialize(file_get_contents($d_table_file_name));

					foreach ($d_table_query as $dq_key => $dq_val)
					{
						if ($dq_val == $mq_val)
						{
							unset($d_table_query[$dq_key]);
						}
					}

					/* Удалить файл если у него уже нет запросов */
					if (empty($d_table_query))
					{
						unlink($this->_cache_dir . "/table/" . $this->_get_cache_file_name_table($d_val));
					}
					else
					{
						file_put_contents($d_table_file_name, serialize($d_table_query));
					}
				}

				/* Удалить файл запроса */
				unlink($mq_file_name);
			}
		}


		return true;
	}

	/**
	 * Получить имя файла таблицы
	 * 
	 * @param string $table
	 * @return string
	 */
	private function _get_cache_file_name_table($table)
	{
		if (empty($table))
		{
			throw new Exception("Имя таблиц не задано.", 171);
		}

		$file_name = md5($this->_host . $this->_db_name . $this->_schema . $table);

		return $file_name;
	}

	/**
	 * Получить имя файла запроса
	 * 
	 * @param string $query
	 * @param string $result_type
	 * @return string
	 */
	private function _get_cache_file_name_query($query, $result_type)
	{
		if (empty($query))
		{
			throw new Exception("Запрос не задан.", 181);
		}

		$file_name = md5($this->_host . $this->_db_name . $this->_schema . $query . $result_type);

		return $file_name;
	}

	/**
	 * Создать папку для кэширования
	 * 
	 * @param string $dir
	 * @return bool
	 */
	private function _cache_dir_create($dir)
	{
		$this->_check_path($dir);
		$dir = trim($dir);
		
		if (!is_dir($dir))
		{
			throw new Exception("Папки \"" . func_get_arg(0) . "\" не существует.", 191);
		}

		if (!is_file($dir . "/.htaccess"))
		{
			$fp = fopen($dir . "/.htaccess", "w");
			fputs($fp, "deny from all", strlen("deny from all"));
			fclose($fp);
		}

		if (!is_dir($dir . "/table"))
		{
			mkdir($dir . "/table");
		}

		if (!is_dir($dir . "/query"))
		{
			mkdir($dir . "/query");
		}

		return true;
	}

	/**
	 * Проверка запроса
	 * 
	 * @param string $query
	 * @return bool
	 */
	private function _check_query($query)
	{
		$query = (string) $query;

		/* Пустая строка */
		$query = trim($query);
		if (empty($query))
		{
			throw new Exception("Запрос задан неверно. Пустая строка.", 201);
		}

		/* Строка с нулевым символом */
		$strlen_before = mb_strlen($query, "UTF-8");
		$query = str_replace(chr(0), '', $query);
		$strlen_after = mb_strlen($query, "UTF-8");
		if ($strlen_before != $strlen_after)
		{
			throw new Exception("Запрос задан неверно. Нулевой символ.", 202);
		}

		/* Бинарная строка, либо символы не в UTF-8 */
		$result = mb_detect_encoding($query, "UTF-8");
		if ($result === false)
		{
			throw new Exception("Запрос задан неверно. Бинарная строка, либо символы не в UTF-8.", 203);
		}

		/* Очень большая строка */
		if (mb_strlen($query, "UTF-8") > 1048576)
		{
			throw new Exception("Запрос задан неверно. Очень большая строка.", 204);
		}

		return true;
	}
	
	/**
	 * Проверка пути
	 * 
	 * @param string $path
	 * @return bool
	 */
	private function _check_path($path)
	{
		$path = (string) $path;

		/* Пустая строка */
		$path = trim($path);
		if (empty($path))
		{
			throw new Exception("Папка задана неверно. Пустая строка.", 211);
		}

		/* Символ "." */
		if ($path == "." or $path == "/")
		{
			throw new Exception("Папка задана неверно. Папка не может быть задана как \".\" или \"/\".", 212);
		}

		/* Строка с нулевым символом */
		$strlen_before = mb_strlen($path, "UTF-8");
		$path = str_replace(chr(0), '', $path);
		$strlen_after = mb_strlen($path, "UTF-8");
		if ($strlen_before != $strlen_after)
		{
			throw new Exception("Путь задан неверно. Нулевой символ.", 213);
		}

		/* Бинарная строка, либо символы не в UTF-8 */
		$result = mb_detect_encoding($path, "UTF-8");
		if ($result === false)
		{
			throw new Exception("Путь задан неверно. Бинарная строка, либо символы не в UTF-8.", 214);
		}

		/* Очень большая строка */
		if (mb_strlen($path, "UTF-8") > 1024)
		{
			throw new Exception("Путь задан неверно. Очень большая строка.", 215);
		}

		/* Недопустимые символы */
		$result = strpbrk($path, "\n\r\t\v\f\$\\");
		if ($result !== false)
		{
			throw new Exception("Путь задан неверно. Недопустимые символы.", 216);
		}

		/* Срезаем символы слэша в начале и конце */
		if (mb_substr($path, 0, 1, "UTF-8") == "/")
		{
			$path = mb_substr($path, 1, mb_strlen($path, "UTF-8") - 1, "UTF-8");
		}

		if (mb_substr($path, mb_strlen($path, "UTF-8") - 1, 1, "UTF-8") == "/")
		{
			$path = mb_substr($path, 0, mb_strlen($path, "UTF-8") - 1, "UTF-8");
		}

		/* Разбор */
		$path_ar = explode("/", $path);
		foreach ($path_ar as $val)
		{
			/* Указание в пути ".." и "." */
			if ($val == "." or $val == "..")
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Использовать имя файла как \"..\" и \".\" запрещено.", 217);
			}

			/* Строка с начальными или конечными пробелами */
			$strlen = mb_strlen($val, "UTF-8");
			$strlen_trim = mb_strlen($val, "UTF-8");
			if ($strlen != $strlen_trim)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Пробелы в начале или в конце имени файла.", 218);
			}

			/* Не указано имя файла */
			$val_trim = trim($val);
			if (empty($val_trim))
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Не задано имя файла.", 219);
			}
		}

		return true;
	}
	
	/**
	 * Запрос на нужную схему
	 * 
	 * @return boolean 
	 */
	private function _query_schema()
	{
		if ($this->_schema != $this->_schema_current and $this->is_connect())
		{
			$query = "SET \"search_path\" TO '" . $this->escape($this->_schema) . "'";
			$result = @pg_query($this->_db_conn, $query);
			if ($result === false)
			{
				throw new Exception("Схема указана неверно. ".pg_last_error($this->_db_conn), 42);
			}
			pg_free_result($result);
			
			$this->_schema_current = $this->_schema;
		}
		
		return true;
	}
}

?>
