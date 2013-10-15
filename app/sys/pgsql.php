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
	 * Разрешать соединение
	 * 
	 * @var bool
	 */
	private $_connection_allow = true;
	
	/**
	 * Использовать кэширование
	 * 
	 * @var bool
	 */
	private $_cache = false;

	/**
	 * Соль для кэша
	 * 
	 * @var string
	 */
	private $_cache_salt;
	
	/**
	 * Тип кэша (file|memcache)
	 * 
	 * @var string
	 */
	private $_cache_type;
	
	/**
	 * Префикс для наименование кэша запросов
	 * 
	 * @var string
	 */
	private $_cache_prefix_query = "query_";
	
	/**
	 * Префикс для наименование кэша таблиц
	 * 
	 * @var string
	 */
	private $_cache_prefix_table = "table_";
	
	/**
	 * Префикс для наименования кэша содержащего все таблицы
	 * 
	 * @var string 
	 */
	private $_cache_prefix_table_all = "table_all_";
	
	/**
	 * Папка с кэшом
	 * 
	 * @var string
	 */
	private $_cache_dir;
	
	/**
	 * Объект memcache
	 * 
	 * @var Memcache
	 */
	private $_memcache_obj;

	/**
	 * Конструктор
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $db_name
	 * @param string $schema
	 * @param int $port
	 * @param bool $persistent
	 * @param string $ssl (disable|prefer|require)
	 * @return bool
	 */
	public function __construct($host, $user, $pass, $db_name, $schema="public", $port=5432, $persistent=false, $ssl="disable")
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
			throw new Exception("Тип подключения по SSL задан неверно. Допустимые значения disable, prefer, require.", 16);
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
		}
		
		if ($this->_schema != $this->_schema_current)
		{
			$query = "SET \"search_path\" TO '" . $this->escape($this->_schema) . "'";
			$result = @pg_query($this->_db_conn, $query);
			if ($result === false)
			{
				throw new Exception("Схема указана неверно. ".pg_last_error($this->_db_conn), 22);
			}
			pg_free_result($result);

			$this->_schema_current = $this->_schema;
		}
		
		$this->_connection_allow = true;

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
		
		$this->_connection_allow = false;

		return true;
	}

	/**
	 * Пересоединение
	 * 
	 * @return bool
	 */
	public function reconnect()
	{
		if(!$this->_connection_allow)
		{
			throw new Exception("Невозможно открыть соединение.", 31);
		}
		
		$this->connect();

		if (!pg_connection_reset($this->_db_conn))
		{
			throw new Exception("Пересоединение не удалось.", 32);
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
	 * Текущая схема
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
	 * @param string $type
	 * @param string $salt
	 * @param string|object $param
	 * @return bool
	 */
	public function cache_enable($type="", $salt="", $param="")
	{
		/* Тип */
		if(empty($type) and empty($this->_cache_type))
		{
			throw new Exception("Не указан тип кэширования", 51);
		}
		
		if(!empty($type))
		{
			$this->_cache_type = $type;
		}
		
		if(!in_array($this->_cache_type, array("file","memcache")))
		{
			throw new Exception("Тип кэша задан неверно. Можно использовать file или memcache.", 52);
		}
		
		/* Соль для хэша файлов */
		if(empty($salt))
		{
			if(empty($this->_cache_salt))
			{
				$this->_cache_salt = md5($_SERVER['SERVER_SOFTWARE'] . " " . php_uname());
			}
		}
		else
		{
			$this->_cache_salt = $salt;
		}
		
		$this->_cache_salt = mb_substr($this->_cache_salt, 0, 32);
		
		/* Папка для кэша */
		if($this->_cache_type == "file")
		{
			$dir = $param;
			
			if(empty($dir) and empty($this->_cache_dir))
			{
				throw new Exception("Не указана папка для кэша.", 53);
			}
			
			if(!empty($dir))
			{
				if(!is_string($dir))
				{
					throw new Exception("Папка указана неверно.", 54);
				}
			
				$dir = trim($dir);
				$dir = realpath($dir);
				
				if (!is_dir($dir))
				{
					throw new Exception("Папки \"" . func_get_arg(2) . "\" не существует.", 55);
				}
				
				$check_file = md5(microtime());
				if(@file_put_contents($dir . "/" . $check_file, "") === false)
				{
					throw new Exception("Папка \"" . func_get_arg(2) . "\" недоступна для записи.", 56);
				}
				unlink($dir . "/" . $check_file);
				
				$this->_cache_dir = $dir;
			}
		}
		/* Объект memcache */
		elseif($type == "memcache")
		{
			$memcache_obj = $param;
			
			if(empty($memcache_obj) and empty($this->_memcache_obj))
			{
				throw new Exception("Не указан объект Memcache.", 57);
			}
			
			if(!empty($memcache_obj))
			{
				if(!is_object($memcache_obj) or get_class($memcache_obj) != "Memcache")
				{
					throw new Exception("Объект Memcache указан неверно.", 58);
				}
				
				$this->_memcache_obj = $memcache_obj;
			}
		}
		
		/* Флаг кэша */
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
		/* Все таблицы */
		$table_all = array();
		if($this->_cache_type == "file")
		{
			if(is_file($this->_cache_dir . "/" . $this->_get_cache_name_table_all()))
			{
				$table_all = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_get_cache_name_table_all()));
			}
		}
		elseif($this->_cache_type == "memcache")
		{
			$memcache_result = $this->_memcache_obj->get($this->_get_cache_name_table_all());
			if($memcache_result !== false)
			{
				$table_all = unserialize($memcache_result);
			}
		}
		
		foreach ($table_all as $t_val)
		{
			/* Запросы по таблице */
			$query = array();
			if($this->_cache_type == "file")
			{
				if (is_file($this->_cache_dir . "/" . $this->_cache_prefix_table . $t_val))
				{
					$query = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_cache_prefix_table . $t_val));
				}
			}
			elseif($this->_cache_type == "memcache")
			{
				$memcache_result = $this->_memcache_obj->get($this->_cache_prefix_table . $t_val);
				if($memcache_result !== false)
				{
					$query = unserialize($memcache_result);
				}
			}
			
			/* Удаление файлов запросов */
			foreach ($query as $q_val)
			{
				if($this->_cache_type == "file")
				{
					@unlink($this->_cache_dir . "/" . $this->_cache_prefix_query . $q_val);
				}
				else
				{
					$this->_memcache_obj->delete($this->_cache_prefix_query . $q_val);
				}
			}
			
			/* Удаление кэша таблиц */
			if($this->_cache_type == "file")
			{
				unlink($this->_cache_dir . "/" . $this->_cache_prefix_table . $t_val);
			}
			elseif($this->_cache_type == "memcache")
			{
				$this->_memcache_obj->delete($this->_cache_prefix_table . $t_val);
			}
		}
		
		/* Удаление кэша всех таблиц */
		if($this->_cache_type == "file")
		{
			unlink($this->_cache_dir . "/" . $this->_get_cache_name_table_all());
		}
		elseif($this->_cache_type == "memcache")
		{
			$this->_memcache_obj->delete($this->_get_cache_name_table_all());
		}
		
		return true;
	}
	
	/**
	 * Удалить кэш у таблицы
	 *  
	 * @return bool
	 */
	public function cache_delete()
	{
		if($this->_cache === false)
		{return true;}
		
		$tables = func_get_args();
		
		if(empty($tables))
		{
			throw new Exception("Не указана таблица, у которой нужно удалить кэш.", 61);
		}
		
		foreach ($tables as $t_val)
		{
			/* Проверка */
			if(!is_string($t_val))
			{
				throw new Exception("Таблицы заданы неверно.", 62);
			}
			
			/* Кэш таблицы */
			$cache_name = $this->_get_cache_name_table($t_val);			
			if($this->_cache_type == "file")
			{
				if(!is_file($this->_cache_dir . "/" . $this->_cache_prefix_table . $cache_name))
				{
					return true;
				}
				else
				{
					$mt_query_ar = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_cache_prefix_table . $cache_name));
				}
			}
			elseif($this->_cache_type == "memcache")
			{
				$memcache_result = $this->_memcache_obj->get($this->_cache_prefix_table . $cache_name);
				if($memcache_result === false)
				{
					return true;
				}
				else
				{
					$mt_query_ar = unserialize($memcache_result);
				}
			}
			
			/* Мои запросы относящиеся к моей таблице */
			foreach ($mt_query_ar as $mq_val)
			{
				/* Таблицы привязанные к моему запросу */
				if($this->_cache_type == "file")
				{
					$d_table_ar = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_cache_prefix_query . $mq_val));
				}
				elseif($this->_cache_type == "memcache")
				{
					$d_table_ar = unserialize($this->_memcache_obj->get($this->_cache_prefix_query . $mq_val));
				}

				/* Удалить пометку запроса в файле таблицы */
				foreach ($d_table_ar['tables'] as $d_val)
				{
					$d_table_cache_name = $this->_get_cache_name_table($d_val);
					
					if($this->_cache_type == "file")
					{
						$d_table_query = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_cache_prefix_table . $d_table_cache_name));
					}
					elseif($this->_cache_type == "memcache")
					{
						$d_table_query = unserialize($this->_memcache_obj->get($this->_cache_prefix_table . $d_table_cache_name));
					}

					foreach ($d_table_query as $dq_key => $dq_val)
					{
						if ($dq_val == $mq_val)
						{
							unset($d_table_query[$dq_key]);
						}
					}

					/* Удалить файл таблицы если у него уже нет запросов */
					if (empty($d_table_query))
					{
						$this->_cache_table_delete($d_table_cache_name);
					}
					/* Перезаписать файл таблицы */
					else
					{
						if($this->_cache_type == "file")
						{
							file_put_contents($this->_cache_dir . "/" . $this->_cache_prefix_table . $d_table_cache_name, serialize($d_table_query));
						}
						elseif($this->_cache_type == "memcache")
						{
							$this->_memcache_obj->set($this->_cache_prefix_table . $d_table_cache_name, serialize($d_table_query));
						}
					}
				}

				/* Удалить файл запроса */
				if($this->_cache_type == "file")
				{
					unlink($this->_cache_dir . "/" . $this->_cache_prefix_query . $mq_val);
				}
				elseif($this->_cache_type == "memcache")
				{
					$this->_memcache_obj->delete($this->_cache_prefix_query . $mq_val);
				}
			}
		}
		
		return true;
	}

	/**
	 * Используется ли кэширование
	 * 
	 * @return bool
	 */
	public function is_cache()
	{
		return $this->_cache;
	}
	
	/**
	 * Показать тип кэширования
	 * 
	 * @return string|bool
	 */
	public function get_cache_type()
	{
		if($this->_cache)
		{
			return $this->_cache_type;
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * Выполнить запрос
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @return bool
	 */
	public function query($query, $params=null, $tables=null)
	{
		return $this->_query("simple", $query, $params, $tables);
	}

	/**
	 * Выполнить запрос и вернуть ассоциативный массив
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @param string $cache_time
	 * @return array
	 */
	public function query_assoc($query, $params=null, $tables=null, $cache_time="+1 month")
	{
		return $this->_query("assoc", $query, $params, $tables, $cache_time);
	}

	/**
	 * Выполнить запрос и вернуть первый столбец в обычный массив
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @param string $cache_time
	 * @return array 
	 */
	public function query_column($query, $params=null, $tables=null, $cache_time="+1 month")
	{
		return $this->_query("column", $query, $params, $tables, $cache_time);
	}

	/**
	 * Выполнить запрос и вернуть первую строку в ассоциативном массиве
	 * где индекс наименование столбца
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @param string $cache_time
	 * @return array
	 */
	public function query_line($query, $params=null, $tables=null, $cache_time="+1 month")
	{
		return $this->_query("line", $query, $params, $tables, $cache_time);
	}

	/**
	 * Выполнить запрос и вернуть первый столбец в первой строке
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @param string $cache_time
	 * @return string
	 */
	public function query_one($query, $params=null, $tables=null, $cache_time="+1 month")
	{
		return $this->_query("one", $query, $params, $tables, $cache_time);
	}
	
	/**
	 * Выполнить запрос и вернуть объект
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @param string $cache_time
	 * @return object
	 */
	public function query_object($query, $params=null, $tables=null, $cache_time="+1 month")
	{
		return $this->_query("object", $query, $params, $tables, $cache_time);
	}
	
	/**
	 * Выполнить запрос и вернуть массив объектов
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @param string $cache_time
	 * @return array
	 */
	public function query_object_ar($query, $params=null, $tables=null, $cache_time="+1 month")
	{
		return $this->_query("object_ar", $query, $params, $tables, $cache_time);
	}

	/**
	 * Выполнить запрос и вернуть ресурс результата запроса
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @return resource
	 */
	public function query_result($query, $params=null)
	{
		return $this->_query("result", $query, $params);
	}
	
	/**
	 * Множественный запрос
	 * 
	 * @param string $query
	 * @return bool
	 */
	public function multi_query($query)
	{
		/* Проверка (32 Мб) */
		$this->_check_query($query, 33554432);
		
		/* Запрос */
		if(!$this->_connection_allow)
		{
			throw new Exception("Невозможно открыть соединение.", 71);
		}
		
		$this->connect();

		$result = @pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 72);
		}
		pg_free_result($result);
		
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
			throw new Exception("Не задано имя таблицы.", 81);
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
	 * @param string $table
	 * @param string $column
	 * @return bool
	 */
	public function is_column($table, $column)
	{
		$table = trim($table);
		if (empty($table))
		{
			throw new Exception("Не задано имя таблицы.", 91);
		}

		$column = trim($column);
		if (empty($column))
		{
			throw new Exception("Не задано имя столбца.", 92);
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
	 * Запрос INSERT
	 * 
	 * @param string $table
	 * @param array $data
	 * @param string $return
	 * @return bool|string
	 */
	public function insert($table, $data, $return="")
	{
		/* Проверка */
		if(!is_string($table))
		{
			throw new Exception("Таблица для INSERT задана неверно.", 101);
		}
		
		if(!is_array($data))
		{
			throw new Exception("Данные для INSERT заданы неверно.", 102);
		}
		
		if(!is_string($return))
		{
			throw new Exception("Возвращаемые поле задано неверно.", 103);
		}
		
		/* Формирование запроса */
		$stolb = array(); 
		$param = array(); 
		$param_int = array();
		$i = 1;
		
		foreach ($data as $key => $val)
		{
			$stolb[] = $key;
			$param[] = $val;
			$param_int[] = $i;
			$i++;
		}
		
		$sql_stolb = "\"".implode("\", \"", $stolb)."\"";
		$sql_values = "\$".implode(", \$", $param_int);
		
		/* Запрос */
		$query = 
<<<SQL
INSERT INTO "{$table}" ({$sql_stolb})
VALUES ({$sql_values})
SQL;
		
		/* Возвращаемые значения */
		if(!empty($return))
		{
			$query .= "\nRETURNING \"{$return}\"";
			$result = $this->query_one($query, $param);
			$this->cache_delete($table);
			return $result;
		}
		else
		{
			$this->query($query, $param, $table);
			return true;
		}
	}
	
	/**
	 * Запрос UPDATE
	 * 
	 * @param string $table
	 * @param array $data
	 * @param array $where
	 * @return bool
	 */
	public function update($table, $data, $where)
	{
		/* Проверка */
		if(!is_string($table))
		{
			throw new Exception("Таблица для UPDATE задана неверно.", 111);
		}
		
		if(!is_array($data))
		{
			throw new Exception("Данные для UPDATE заданы неверно.", 112);
		}
		
		if(!is_array($where))
		{
			throw new Exception("Условия для UPDATE заданы неверно.", 113);
		}
		
		/* Формирование запроса */
		$sql_values = "";
		$param = array();
		$i = 1;
		
		foreach ($data as $key => $val)
		{
			$sql_values .= "\t\"{$key}\" = \${$i}";
			if($i != count($data))
			{
				$sql_values .= ",\n";
			}
			$i++;
			$param[] = $val;
		}
		
		$where_first = true;
		$sql_where = "";
		
		foreach ($where as $key => $val)
		{
			if(!$where_first)
			{
				$sql_where .= "AND ";
			}
			
			$sql_where .= "\"{$key}\" = \${$i}\n";
			 
			$i++;
			$param[] = $val;
			$where_first = false;
		}
		
		/* Запрос */
		$query = 
<<<SQL
UPDATE "{$table}"
SET 
{$sql_values}
WHERE {$sql_where}
SQL;
		$this->query($query, $param, $table);
		
		return true;
	}
	
	/**
	 * Запрос DELETE
	 * 
	 * @param string $table
	 * @param array $where
	 * @return bool
	 */
	public function delete($table, $where)
	{
		/* Проверка */
		if(!is_string($table))
		{
			throw new Exception("Таблица для DELETE задана неверно.", 121);
		}
		
		if(!is_array($where))
		{
			throw new Exception("Условия для DELETE заданы неверно.", 122);
		}
		
		/* Формирование запроса */
		$param = array();
		$i = 1;
		$where_first = true;
		$sql_where = "";
		
		foreach ($where as $key => $val)
		{
			if(!$where_first)
			{
				$sql_where .= "AND ";
			}
			
			$sql_where .= "\"{$key}\" = \${$i}\n";
			 
			$param[] = $val;
			$i++;
			$where_first = false;
		}
		
		/* Запрос */
		$query = 
<<<SQL
DELETE
FROM "{$table}"
WHERE {$sql_where}
SQL;
		$this->query($query, $param, $table);
		
		return true;
	}

	/**
	 * Один метод для всех методов query*
	 * 
	 * @param string $type
	 * @param string $query
	 * @param string|array $params
	 * @param string|array $tables
	 * @param string $cache_time 
	 * @return mixed
	 */
	private function _query($type, $query, $params=null, $tables=null, $cache_time="+1 month")
	{
		/* Проверка */
		if(!in_array($type, array("assoc","column","line","one","object","object_ar","simple","result")))
		{
			throw new Exception("Тип запроса " . func_get_arg(0) . " задан неверно.", 131);
		}
		
		$this->_check_query($query);
		
		if (!empty($params))
		{
			if(!is_scalar($params) and !is_array($params))
			{
				throw new Exception("Параметры для запроса заданы неверно.", 132);
			}
			
			if(is_scalar($params))
			{
				$params = array($params);
			}
		}
		else 
		{
			$params = array();
		}
		
		if (!empty($tables))
		{
			if (!is_array($tables) and !is_string($tables))
			{
				throw new Exception("Таблицы заданы неверно.", 133);
			}

			if (is_string($tables))
			{
				$tables = array($tables);
			}
		}
		else 
		{
			$tables = array();
		}
		
		$cache_time = trim($cache_time);
		$cache_time = strtotime($cache_time);
		if($cache_time === false)
		{
			throw new Exception("Время хранения кэша \"" . func_get_arg(4) . "\" задано неверно.", 134);
		}
		
		/* Берём данные из кэша */
		if 
		(
			$this->_cache == true and
			!empty($tables) and 
			!in_array($type, array("simple","result"))
		)
		{
			/* Кэш из файла */
			if($this->_cache_type == "file")
			{
				$cache_file = $this->_cache_dir . "/" . $this->_cache_prefix_query . $this->_get_cache_name_query($query, $type, $params);
				if(is_file($cache_file))
				{
					$cache_result = unserialize(file_get_contents($cache_file));
					if(time() <= $cache_result['time'])
					{
						return $cache_result['result'];
					}
					else
					{
						unlink($cache_file);
					}
				}
			}
			/* Кэш из памяти */
			elseif($this->_cache_type == "memcache")
			{
				$memcache_result_str = $this->_memcache_obj->get($this->_cache_prefix_query . $this->_get_cache_name_query($query, $type, $params));
				if($memcache_result_str !== false)
				{
					$memcache_result = unserialize($memcache_result_str);
					return $memcache_result['result'];
				}
			}
		}

		/* Запрос */
		if(!$this->_connection_allow)
		{
			throw new Exception("Невозможно открыть соединение.", 135);
		}
		
		$this->connect();

		if(!empty($params))
		{
			$result = @pg_query_params($this->_db_conn, $query, $params);
		}
		else
		{
			$result = @pg_query_params($this->_db_conn, $query, array());
		}
		
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. ".pg_last_error($this->_db_conn), 136);
		}

		/* Результат запроса */
		switch ($type)
		{
			case "assoc":
			{
				$data = array();
				while ($row = pg_fetch_assoc($result))
				{
					$data[] = $row;
				}
			}
			break;
		
			case "column":
			{
				$data = array();
				while ($row = pg_fetch_row($result))
				{
					$data[] = $row[0];
				}
			}
			break;
		
			case "line":
			{
				$data = pg_fetch_assoc($result);
			}
			break;
		
			case "one":
			{
				$row = pg_fetch_row($result);
				if (!isset($row[0]))
				{
					$row[0] = null;
				}
				$data = $row[0];
			}
			break;
		
			case "object":
			{
				$data = pg_fetch_object($result);
			}
			break;
		
			case "object_ar":
			{
				$data = array();
				while ($obj = pg_fetch_object($result))
				{
					$data[] = $obj;
				}
			}
			break;
		
			case "result":
			{
				return $result;
			}
			break;
		}
		
		/* Освобождаем память занятую ресурсом */
		pg_free_result($result);
		
		/* Кэш */
		if($this->_cache == true and !empty($tables))
		{
			/* Создаём кэш */
			if(in_array($type, array("assoc","column","line","one","object","object_ar")))
			{
				$this->_cache_create($query, $params, $tables, $data, $type, $cache_time);
			}
			/* Удаляем кэш */
			elseif($type == "simple")
			{
				foreach ($tables as $val)
				{
					$this->cache_delete($val);
				}
			}
		}
		
		/* Возвращаем значение */
		if($type != "simple")
		{return $data;}
		else
		{return true;}
	}
	
	/**
	 * Проверка запроса
	 * 
	 * @param string $query
	 * @param int $max_length
	 * @return bool
	 */
	private function _check_query($query, $max_length = 1048576)
	{
		$query = (string) $query;

		/* Пустая строка */
		$query = trim($query);
		if (empty($query))
		{
			throw new Exception("Запрос задан неверно. Пустая строка.", 141);
		}

		/* Строка с нулевым символом */
		$strlen_before = mb_strlen($query);
		$query = str_replace(chr(0), '', $query);
		$strlen_after = mb_strlen($query);
		if ($strlen_before != $strlen_after)
		{
			throw new Exception("Запрос задан неверно. Нулевой символ.", 142);
		}

		/* Бинарная строка, либо символы не в UTF-8 */
		$result = mb_detect_encoding($query, "UTF-8");
		if ($result === false)
		{
			throw new Exception("Запрос задан неверно. Бинарная строка, либо символы не в UTF-8.", 143);
		}

		/* Очень большая строка */
		if (mb_strlen($query) > $max_length)
		{
			throw new Exception("Запрос задан неверно. Очень большая строка.", 144);
		}

		return true;
	}
	/**
	 * Создать кэш запроса
	 * 
	 * @param string $query
	 * @param array $tables
	 * @param array $params
	 * @param mixed $result
	 * @param string $result_type
	 * @param int $time
	 * @return bool
	 */
	private function _cache_create($query, $params, $tables, &$result, $result_type, $time)
	{
		/* Проверка */
		if(!in_array($result_type, array("assoc","column","line","one","object","object_ar")))
		{
			throw new Exception("Тип результата задан неверно.", 151);
		}
		
		/* Кэш запроса */
		$query_data = array();
		$query_data['tables'] = $tables;
		$query_data['result'] = $result;
		$query_data['time'] = $time;
		
		$cache_name_query = $this->_get_cache_name_query($query, $result_type, $params);

		if($this->_cache_type == "file")
		{
			file_put_contents($this->_cache_dir . "/" . $this->_cache_prefix_query . $cache_name_query, serialize($query_data));
		}
		elseif($this->_cache_type == "memcache")
		{
			if($time - time() >= 2592000 or $time - time() <= 0)
			{
				$memcache_time = 2592000;
			}
			else
			{
				$memcache_time = $time - time();
			}
			
			$this->_memcache_obj->set($this->_cache_prefix_query . $cache_name_query, serialize($query_data), false, $memcache_time);
		}

		/* Кэш для таблиц */
		foreach ($tables as $val)
		{
			$cache_name_table = $this->_get_cache_name_table($val);
			$this->_cache_table_add($cache_name_table, $cache_name_query);
		}

		return true;
	}

	/**
	 * Получить имя кэша таблицы
	 * 
	 * @param string $table
	 * @return string
	 */
	private function _get_cache_name_table($table)
	{
		if (empty($table))
		{
			throw new Exception("Имя таблиц не задано.", 161);
		}

		$name = md5($this->_cache_salt . $this->_host . $this->_db_name . $this->_schema . $table);
		
		return $name;
	}

	/**
	 * Получить имя кэша запроса
	 * 
	 * @param string $query
	 * @param string $result_type
	 * @param array $params
	 * @return string
	 */
	private function _get_cache_name_query($query, $result_type, $params)
	{
		if (empty($query))
		{
			throw new Exception("Запрос не задан.", 171);
		}

		$name = md5($this->_cache_salt . $this->_host . $this->_db_name . $this->_schema . $query . $result_type . serialize($params));

		return $name;
	}
	
	/**
	 * Получить имя кэша со всеми таблицами
	 * 
	 * @return string 
	 */
	private function _get_cache_name_table_all()
	{
		return $this->_cache_prefix_table_all . md5($this->_cache_salt . $this->_host . $this->_db_name . $this->_schema);
	}
	
	/**
	 * Добавить запрос в кэш таблицы
	 * 
	 * @param string $cache_name_table
	 * @param array $cache_name_query
	 * @return boolean 
	 */
	private function _cache_table_add($cache_name_table, $cache_name_query)
	{
		/* Запросы по таблице */
		$table_query = array();
		if($this->_cache_type == "file")
		{
			if (is_file($this->_cache_dir . "/" . $this->_cache_prefix_table . $cache_name_table))
			{
				$table_query = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_cache_prefix_table . $cache_name_table));
			}
		}
		elseif($this->_cache_type == "memcache")
		{
			$memcache_result = $this->_memcache_obj->get($this->_cache_prefix_table . $cache_name_table);
			if($memcache_result !== false)
			{
				$table_query = unserialize($memcache_result);
			}
		}

		/* Добавить запрос */
		if (!in_array($cache_name_query, $table_query))
		{
			$table_query[] = $cache_name_query;
		}

		/* Перезаписать кэш таблицы */
		if($this->_cache_type == "file")
		{
			file_put_contents($this->_cache_dir . "/" . $this->_cache_prefix_table . $cache_name_table, serialize($table_query));
		}
		elseif($this->_cache_type == "memcache")
		{
			$this->_memcache_obj->set($this->_cache_prefix_table . $cache_name_table, serialize($table_query));
		}
		
		/* Добавить в список всех таблиц */
		$table_all = array();
		if($this->_cache_type == "file")
		{
			if(is_file($this->_cache_dir . "/" . $this->_get_cache_name_table_all()))
			{
				$table_all = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_get_cache_name_table_all()));
			}
		}
		elseif($this->_cache_type == "memcache")
		{
			$memcache_result = $this->_memcache_obj->get($this->_get_cache_name_table_all());
			if($memcache_result !== false)
			{
				$table_all = unserialize($memcache_result);
			}
		}
		
		if(!in_array($cache_name_table, $table_all))
		{
			$table_all[] = $cache_name_table;
		}
		
		if($this->_cache_type == "file")
		{
			file_put_contents($this->_cache_dir . "/" . $this->_get_cache_name_table_all(), serialize($table_all));
		}
		elseif($this->_cache_type == "memcache")
		{
			$this->_memcache_obj->set($this->_get_cache_name_table_all(), serialize($table_all));
		}
		
		return true;
	}
	
	/**
	 * Удалить кэш таблицы
	 * 
	 * @param string $cache_name_table
	 * @return boolean 
	 */
	private function _cache_table_delete($cache_name_table)
	{
		/* Удалить кэш таблицы */
		if($this->_cache_type == "file")
		{
			unlink($this->_cache_dir . "/" . $this->_cache_prefix_table . $cache_name_table);
		}
		elseif($this->_cache_type == "memcache")
		{
			$this->_memcache_obj->delete($this->_cache_prefix_table . $cache_name_table);
		}
		
		/* Удалить таблицу из списка всех таблиц memcache */
		$table_all = array();
		if($this->_cache_type == "file")
		{
			if(is_file($this->_cache_dir . "/" . $this->_get_cache_name_table_all()))
			{
				$table_all = unserialize(file_get_contents($this->_cache_dir . "/" . $this->_get_cache_name_table_all()));
			}
		}
		elseif($this->_cache_type == "memcache")
		{
			$memcache_result = $this->_memcache_obj->get($this->_get_cache_name_table_all());
			if($memcache_result !== false)
			{
				$table_all = unserialize($memcache_result);
			}
		}
		
		foreach ($table_all as $key=>$val)
		{
			if($val == $cache_name_table)
			{
				unset($table_all[$key]);
			}
		}

		/* Перезаписать кэш со всеми таблицами */
		if(!empty($table_all))
		{
			if($this->_cache_type == "file")
			{
				file_put_contents($this->_cache_dir . "/" . $this->_get_cache_name_table_all(), serialize($table_all));
			}
			elseif($this->_cache_type == "memcache")
			{
				$this->_memcache_obj->set($this->_get_cache_name_table_all(), serialize($table_all));
			}
		}
		/* Удалить кэш со всеми таблицами, т.к. пустой */
		else
		{
			if($this->_cache_type == "file")
			{
				unlink($this->_cache_dir . "/" . $this->_get_cache_name_table_all());
			}
			elseif($this->_cache_type == "memcache")
			{
				$this->_memcache_obj->delete($this->_get_cache_name_table_all());
			}
		}
		
		return true;
	}
}
?>