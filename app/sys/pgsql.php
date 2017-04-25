<?php
/**
 * Класс для работы с PostgreSQL
 * 
 * @category	Databases
 * @author		Sergeev Denis <hharek@yandex.ru>
 * @copyright	2011 - 2016 Sergeev Denis
 * @license		https://github.com/hharek/zn_pgsql/wiki/MIT-License MIT License
 * @version		2.0
 * @link		https://github.com/hharek/zn_pgsql/
 */
class _PgSQL
{
	/**
	 * Ресурс подключения
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
	 * Объект результат
	 * 
	 * @var ZN_PgSQL_Result
	 */
	private $_result;
	
	/**
	 * Вести отчёт
	 * 
	 * @var bool 
	 */
	private $_log = false;
	
	/**
	 * Файл отчёта
	 * 
	 * @var string
	 */
	private $_log_file;
	
	/**
	 * Список строк с наименованиями текущих схем объектов. Служит для линкования при клонировании
	 * 
	 * @var array
	 */
	private static $_schema_current_list;

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
	 */
	public function __construct
	(
		string $host, 
		string $user, 
		string $pass, 
		string $db_name, 
		string $schema = "public", 
		int $port = 5432, 
		bool $persistent = false, 
		string $ssl = "disable"
	)
	{
		/* Проверка */
		if (empty($host))
		{
			throw new Exception("Хост не задан.");
		}

		if (empty($user))
		{
			throw new Exception("Пользователь не задан.");
		}

		if (empty($db_name))
		{
			throw new Exception("Наименование базы не задано.");
		}

		if (empty($schema))
		{
			throw new Exception("Схема задана неверно.");
		}

		if ($port === 0)
		{
			throw new Exception("Порт задан неверно.");
		}

		if (!in_array($ssl, ["disable", "prefer", "require"]))
		{
			throw new Exception("Тип подключения по SSL задан неверно. Допустимые значения disable, prefer, require.");
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
		
		/* У клонов _schema_current должна ссылаться на одну строку */
		self::$_schema_current_list[] = "public";
		$this->_schema_current = &self::$_schema_current_list[max(self::$_schema_current_list)];
	}
	
	/**
	 * Деструктор
	 */
	public function __destruct()
	{
		$this->close();
	}
	
	/**
	 * Исполнение функций
	 * 
	 * @param string $func
	 * @param array $args
	 * @return ZN_PgSQL_Result
	 */
	public function __call (string $func, array $args) : ZN_PgSQL_Result
	{
		/* Создать запрос для функции */
		$args_number_str = "";
		if (!empty($args))
		{
			$args_number_ar = array();
			for($i = 1; $i <= count($args); $i++)
			{
				$args_number_ar[] = $i;
			}
			$args_number_str = '$' . implode(', $', $args_number_ar);
		}
		
		$query = 
<<<SQL
SELECT * FROM {$func}({$args_number_str});
SQL;

		/* Выполнить запрос */
		return $this->query($query, $args);
	}
	
	/**
	 * Соединение
	 * 
	 * @return boolean
	 */
	public function connect() : bool
	{
		if (!$this->is_connect())
		{
			$str_connect = "host={$this->_host} user={$this->_user} password={$this->_pass} dbname={$this->_db_name} port={$this->_port} sslmode={$this->_ssl}";

			if ($this->_persistent)
			{
				$this->_db_conn = pg_pconnect($str_connect);
			}
			else
			{
				$this->_db_conn = pg_connect($str_connect);
			}

			if (!$this->_db_conn)
			{
				$error = error_get_last();
				throw new Exception("Не удалось установить соединение. " . $error['message']);
			}
		}
		
		if ($this->_schema != $this->_schema_current)
		{
			$query = "SET \"search_path\" TO '" . $this->escape($this->_schema) . "'";
			$result = pg_query($this->_db_conn, $query);
			if ($result === false)
			{
				throw new Exception("Схема указана неверно. " . pg_last_error($this->_db_conn));
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
	 * @return boolean
	 */
	public function close() : bool
	{
		if ($this->is_connect())
		{
			pg_close($this->_db_conn);
		}
		
		$this->_connection_allow = false;
		
		return true;
	}

	/**
	 * Пересоединение
	 * 
	 * @return boolean
	 */
	public function reconnect() : bool
	{
		if (!$this->_connection_allow)
		{
			throw new Exception("Невозможно открыть соединение.");
		}
		
		$this->connect();
		$this->_schema_current = "public";

		if (!pg_connection_reset($this->_db_conn))
		{
			throw new Exception("Пересоединение не удалось.");
		}
		
		return true;
	}

	/**
	 * Проверить соединение
	 * 
	 * @return boolean
	 */
	public function is_connect() : bool
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
	 * Показать текущую схему или назначить новую
	 * 
	 * @param string $schema
	 * @return string
	 */
	public function schema (string $schema = "") : string
	{
		/* Назначить схему */
		if (!empty($schema))
		{
			$schema = trim($schema);

			if (empty($schema))
			{
				throw new Exception("Схема не задана.");
			}

			$this->_schema = $schema;
		}
		
		/* Вернуть имя схемы */
		return $this->_schema;
	}
	
	/**
	 * Один метод для всех методов query*
	 * 
	 * @param string $query
	 * @param string|array $params
	 * @return ZN_PgSQL_Result
	 */
	public function query (string $query, $params = null) : ZN_PgSQL_Result
	{
		/* Проверка */
		$this->_check_query($query);
		
		if ($params !== null)
		{
			if (!is_scalar($params) and !is_array($params))
			{
				throw new Exception("Параметры для запроса заданы неверно.");
			}
			
			if (is_scalar($params))
			{
				$params = [$params];
			}
		}
		else 
		{
			$params = [];
		}
		
		/* Запрос */
		if(!$this->_connection_allow)
		{
			throw new Exception("Невозможно открыть соединение.");
		}
		
		$this->connect();

		if(!empty($params))
		{
			$result = pg_query_params($this->_db_conn, $query, $params);
		}
		else
		{
			$result = pg_query_params($this->_db_conn, $query, []);
		}
		
		/* Отчёт */
		$this->_log($query, $params);
		
		/* Ошибка */
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. " . pg_last_error($this->_db_conn));
		}

		/* Создаём объект для результатов */
		if(empty($this->_result))
		{
			$this->_result = new ZN_PgSQL_Result();
		}
		
		/* Помещаем результат запроса в переменную */
		$this->_result->_pgsql_result = $result;
		
		/* Возвращаем значение */
		return $this->_result;
	}
	
	/**
	 * Множественный запрос
	 * 
	 * @param string $query
	 * @return boolean
	 */
	public function multi_query (string $query) : bool
	{
		/* Проверка (32 Мб) */
		$this->_check_query($query, 33554432);
		
		/* Запрос */
		if (!$this->_connection_allow)
		{
			throw new Exception("Невозможно открыть соединение.");
		}
		
		$this->connect();

		$result = pg_query($this->_db_conn, $query);
		if ($result === false)
		{
			throw new Exception("Ошибка в запросе. " . pg_last_error($this->_db_conn));
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
	public function escape (string $str) : string 
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
	 * Запрос INSERT
	 * 
	 * @param string $table
	 * @param array $data
	 * @param string $return
	 * @return string
	 */
	public function insert (string $table, array $data, string $return = null) : string
	{
		/* Проверка */
		if (!is_string($table))
		{
			throw new Exception("Таблица для INSERT задана неверно.");
		}
		
		if (!is_array($data))
		{
			throw new Exception("Данные для INSERT заданы неверно.");
		}
		
		if ($return !== null and !is_string($return))
		{
			throw new Exception("Возвращаемые поле задано неверно.");
		}
		
		/* Формирование запроса */
		$stolb = []; $param = []; $param_int = [];
		$i = 1;
		foreach ($data as $key => $val)
		{
			$stolb[] = $key;
			$param[] = $val;
			$param_int[] = $i;
			$i++;
		}
		
		$sql_stolb = '"' . implode('", "', $stolb) . '"';
		$sql_values = '$' . implode(', $', $param_int);
		
		/* Запрос */
		$query = 
<<<SQL
INSERT INTO "{$table}" ({$sql_stolb})
VALUES ({$sql_values})
SQL;
		
		/* Возвращаемые значения */
		if ($return !== null)
		{
			$query .= "\nRETURNING \"{$return}\"";
			$result = $this->query($query, $param)->single();
			
			return $result;
		}
		else
		{
			$this->query($query, $param);
		}
		
		return "";
	}
	
	/**
	 * Запрос UPDATE
	 * 
	 * @param string $table
	 * @param array $data
	 * @param array $where
	 * @return boolean
	 */
	public function update (string $table, array $data, array $where) : bool
	{
		/* Проверка */
		if (!is_string($table))
		{
			throw new Exception("Таблица для UPDATE задана неверно.");
		}
		
		if (!is_array($data))
		{
			throw new Exception("Данные для UPDATE заданы неверно.");
		}
		
		if (!is_array($where))
		{
			throw new Exception("Условия для UPDATE заданы неверно.");
		}
		
		/* Формирование запроса */
		$sql_values = "";
		$param = [];
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
		$this->query($query, $param);
		
		return true;
	}
	
	/**
	 * Запрос DELETE
	 * 
	 * @param string $table
	 * @param array $where
	 * @return boolean
	 */
	public function delete (string $table, array $where) : bool
	{
		/* Проверка */
		if (!is_string($table))
		{
			throw new Exception("Таблица для DELETE задана неверно.");
		}
		
		if (!is_array($where))
		{
			throw new Exception("Условия для DELETE заданы неверно.");
		}
		
		/* Формирование запроса */
		$param = [];
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
		$this->query($query, $param);
		
		return true;
	}

	/**
	 * Включить отчёт
	 * 
	 * @param string $file
	 * @return boolean
	 */
	public function log_enable(string $file = null) : bool
	{
		if ($file === null and $this->_log_file === null)
		{
			throw new Exception("Укажите файл для отчёта.");
		}
		
		if ($file !== null)
		{
			/* Создать файл отчёта */
			if (!is_file($file))
			{
				if (touch($file) === false)
				{
					throw new Exception("Невозможно создать файл отчёта.");
				}
			}
			
			$file = realpath($file);
			
			/* Назначить дату начала отчёта */
			if (filesize($file) !== 0)
			{
				file_put_contents($file, "\n\n", FILE_APPEND);
			}
			file_put_contents($file, "------------------- " . date("d.m.Y - H:i:s") . " --------------------\n", FILE_APPEND);
			
			$this->_log_file = $file;
		}
		
		$this->_log = true;
		
		return true;
	}
	
	/**
	 * Отключить отчёт
	 * 
	 * @return boolean
	 */
	public function log_disable() : bool
	{
		$this->_log = false;
		return true;
	}
	
	/**
	 * Вернуть ресурс подключения
	 * 
	 * @return resource
	 */
	public function get_db_conn()
	{
		$this->connect();
		return $this->_db_conn;
	}

	/**
	 * Проверка запроса
	 * 
	 * @param string $query
	 * @param int $max_length
	 * @return boolean
	 */
	private function _check_query (string $query, int $max_length = 1048576) : bool
	{
		/* Пустая строка */
		$query = trim($query);
		if (empty($query))
		{
			throw new Exception("Запрос задан неверно. Пустая строка.");
		}

		/* Строка с нулевым символом */
		$strlen_before = mb_strlen($query);
		$query = str_replace(chr(0), '', $query);
		$strlen_after = mb_strlen($query);
		if ($strlen_before != $strlen_after)
		{
			throw new Exception("Запрос задан неверно. Нулевой символ.");
		}

		/* Бинарная строка, либо символы не в UTF-8 */
		$result = mb_detect_encoding($query, "UTF-8");
		if ($result === false)
		{
			throw new Exception("Запрос задан неверно. Бинарная строка, либо символы не в UTF-8.");
		}

		/* Очень большая строка */
		if (mb_strlen($query) > $max_length)
		{
			throw new Exception("Запрос задан неверно. Очень большая строка.");
		}
		
		return true;
	}
	
	/**
	 * Записать запрос в отчёт
	 * 
	 * @param string $query
	 * @param array $params
	 * @return boolean
	 */
	private function _log(string $query, array $params) : bool
	{
		/* Отчёт отключён */
		if ($this->_log !== true)
		{
			return false;
		}
		
		/* Подготовить запрос */
		$search = []; $replace = [];
		foreach ($params as $key => $val)
		{
			$key = $key + 1;
			$search[] = "$" . $key;
			$replace[] = "'" . $this->escape($val). "'";
		}
		$query = str_replace($search, $replace, $query);
		$query = $query . "\n--------------------------------------------------------------\n" ;
		
		/* Записать запрос */
		file_put_contents($this->_log_file, $query, FILE_APPEND);
		
		return true;
	}
}

/**
 * Класс для работы с результатами запроса
 */
class ZN_PgSQL_Result
{
	/**
	 * Результат запроса
	 * 
	 * @var resource 
	 */
	public $_pgsql_result;
	
	/**
	 * Деструктор
	 */
	public function __destruct()
	{
		if ($this->_pgsql_result !== null)
		{
			$this->_free_result();
		}
	}

	/**
	 * Вернуть все данные в виде ассоциативный массив
	 * 
	 * @return array
	 */
	public function assoc() : array
	{
		$data = pg_fetch_all($this->_pgsql_result);
		$this->_free_result();
		
		if ($data !== false)
		{
			return $data;
		}
		else
		{
			return [];
		}
	}
	
	/**
	 * Вернуть первый столбец в виде массива
	 * 
	 * @return array
	 */
	public function column() : array
	{
		$data = pg_fetch_all_columns($this->_pgsql_result);
		$this->_free_result();
		
		if ($data !== false)
		{
			return $data;
		}
		else
		{
			return [];
		}
	}
	
	/**
	 * Вернуть первую строку в виде ассоциативного массива
	 * 
	 * @return array
	 */
	public function row() : array
	{
		$data = pg_fetch_assoc($this->_pgsql_result);
		$this->_free_result();
		
		if($data !== false)
		{
			return $data;
		}
		else
		{
			return [];
		}
	}
	
	/**
	 * Вернуть первый столбец в первой строке
	 * 
	 * @return string
	 */
	public function single()
	{
		$row = pg_fetch_row($this->_pgsql_result);
		if (isset($row[0]))
		{
			return $row[0];
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * Вернуть результат запроса
	 * 
	 * @return resource
	 */
	public function result()
	{
		return $this->_pgsql_result;
	}
	
	/**
	 * Очистить результат запроса
	 * 
	 * @return boolean
	 */
	private function _free_result() : bool
	{
		pg_free_result($this->_pgsql_result);
		$this->_pgsql_result = null;
		
		return true;
	}
}
?>