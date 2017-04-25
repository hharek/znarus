<?php
/**
 * Версии данных
 */
class _Version
{
	/**
	 * Префикс для наименования ключей
	 * 
	 * @var string
	 */
	private $_prefix = "version";

	/**
	 * Соль для наименования файлов
	 * 
	 * @var string
	 */
	private $_salt;

	/**
	 * Тип хранения данных (file | dba)
	 * 
	 * @var string
	 */
	private $_type;

	/**
	 * Формат даты
	 * 
	 * @var string
	 */
	private $_date_format = "Y-m-d H:i:s";

	/**
	 * Максимальное кол-во версий на сущность
	 *  
	 * @var type 
	 */
	private $_max_count = 10;

	/**
	 * Папка для хранения файлов
	 * 
	 * @var string
	 */
	private $_file_dir;

	/**
	 * Файл dba
	 * 
	 * @var string
	 */
	private $_dba_file;
	
	/**
	 * Тип dba файла (qdbm, db4).
	 * 
	 * @var string
	 */
	private $_dba_type = "qdbm";
	
	/**
	 * Размер файла в мегобайтах после чего происходит оптимизация
	 * 
	 * @var int
	 */
	private $_dba_file_size_optimize = 100;
	
	/**
	 * Сжимать данные (http://php.net/zlib)
	 * 
	 * @var boolean
	 */
	private $_compress_enable = true;
	
	/**
	 * Уровень сжатия от 0 до 9, -1 по умолчанию у zlib
	 * 
	 * @var int 
	 */
	private $_compress_level = -1;
	
	/**
	 * Конструктор
	 * 
	 * @param string $salt
	 * @param string $type (file|dba)
	 * @param mixed $param
	 */
	public function __construct($salt, $type, $param, $compress = true)
	{
		/* Соль */
		$this->_salt = trim((string)$salt);
		
		/* Тип */
		if (!in_array($type, ["file", "dba"]))
		{
			throw new Exception("Тип хранения данных задан неверно.");
		}
		$this->_type = $type;
		
		/* Папка для хранения файлов */
		if ($type === "file")
		{
			$dir = $param;
			
			if (empty($dir))
			{
				throw new Exception("Папка для хранения файлов не указана");
			}

			if (!is_dir($dir))
			{
				throw new Exception("Папки «{$dir}» не существует.");
			}

			$this->_file_dir = realpath($dir);
		}
		/* Ресурс dba */
		elseif ($type === "dba")
		{
			/* Разбираем параметры */
			if (is_string($param))
			{
				$param = (array)$param;
			}

			$dba_file = $param[0];

			$dba_type = $this->_dba_type;
			if (isset($param[1]))
			{
				$dba_type = $param[1];
			}

			/* Проверяем тип */
			if (!in_array($dba_type, ["qdbm","db4"]))
			{
				throw new Exception("Тип dba файла указан неверно. Доступно: qdbm, db4");
			}
			$this->_dba_type = $dba_type;

			/* Проверяем файл dba */
			if (!is_file($dba_file))
			{
				if (($dba = dba_open($dba_file, "c", $this->_dba_type)) === false)
				{
					throw new Exception("Файл dba указан неверно.");
				}
				dba_close($dba);
			}

			$this->_dba_file = $dba_file;
		}
		
		/* Сжатие */
		$compress = (bool)$compress;
		$this->_compress_enable = $compress;
	}

	/**
	 * Существует ли
	 * 
	 * @param string $identified
	 */
	public function is($identified, $date = null)
	{
		/* Проверка даты */
		if ($date !== null)
		{
			$this->_date_check($date);
		}
		
		/* Проверка на существование */
		return $this->_hash_is($this->_key($identified, $date));
	}

	/**
	 * Получить список дат
	 * 
	 * @param string $identified
	 */
	public function get_date_all($identified)
	{
		$result = $this->_hash_get($this->_key($identified));
		if ($result === null)
		{
			return;
		}
		
		return array_reverse(json_decode($result, true));
	}
	
	/**
	 * Получить данные
	 * 
	 * @param string $identified
	 * @param string $date
	 * @return mixed
	 */
	public function get($identified, $date)
	{
		/* Проверка даты */
		if ($date !== null)
		{
			$this->_date_check($date);
		}
		
		/* Данные */
		$result = $this->_hash_get($this->_key($identified, $date));
		if ($result === null)
		{
			return;
		}
		
		return json_decode($result, true);
	}
	
	/**
	 * Назначить данные
	 * 
	 * @param string $identified
	 * @param mixed $data
	 */
	public function set($identified, $data)
	{
		/* Все даты */
		$date_all = $this->get_date_all($identified);
		if ($date_all !== null)
		{
			/* Если данные не изменились */
			if ($this->_hash_get($this->_key($identified, end($date_all))) === json_encode($data))
			{
				return;
			}
			
			/* Максимальное количество версий */
			if (count ($date_all) === $this->_max_count)
			{
				$date_first = array_shift($date_all);
				$this->_hash_delete($this->_key($identified, $date_first));
			}
		}
		else
		{
			$date_all = [];
		}
		
		/* Добавить текущую дату */
		$date = date($this->_date_format);
		if (!in_array($date, $date_all))
		{
			$date_all[] = $date;
		}
		
		/* Назначить данные */
		$this->_hash_set($this->_key($identified), json_encode($date_all));
		$this->_hash_set($this->_key($identified, $date), json_encode($data));
	}

	/**
	 * Удалить
	 * 
	 * @param string $identified
	 */
	public function delete($identified)
	{
		/* Данные */
		$date_all = $this->get_date_all($identified);
		if ($date_all === null)
		{
			return;
		}
		
		/* Удалить ключи с данными */
		foreach ($date_all as $date)
		{
			$this->_hash_delete($this->_key($identified, $date));
		}
		
		/* Удалить ключ с датами */
		$this->_hash_delete($this->_key($identified));
	}
	
	/**
	 * Получить имя ключа
	 * 
	 * @param string $identified
	 * @param string $date
	 * @return string
	 */
	private function _key($identified, $date = null)
	{
		$key = "";
		
		/* Ключ для хранение всех дат версий */
		if ($date === null)
		{
			$key = $this->_prefix . "_" . $identified;
		}
		/* Ключ для хранения данных */
		elseif ($date !== null)
		{
			$key = $this->_prefix . "_" . $identified . "_" . date($this->_date_format, strtotime($date));
		}
		
		/* Хэшируем если указана соль */
		if (!empty($this->_salt))
		{
			$key = md5($key . $this->_salt);
		}
		
		return $key;
	}
	
	/**
	 * Проверка даты
	 * 
	 * @param string $date
	 */
	private function _date_check($date)
	{
		if(strtotime($date) === false)
		{
			throw new Exception("Дата указана неверно.");
		}
	}

	/**
	 * Проверить на существование ключа в словаре
	 * 
	 * @param string $key
	 */
	private function _hash_is($key)
	{
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				return is_file($this->_file_dir . "/" . $key);
			}
			break;

			/* Тип «dba» */
			case "dba":
			{
				$_dba = dba_open($this->_dba_file, "r", $this->_dba_type);
				$result = dba_exists($key, $_dba);
				dba_close($_dba);
				
				return $result;
			}
			break;
		}
	}
	
	/**
	 * Получить значение ключа в словаре
	 * 
	 * @param string $key
	 * @return string
	 */
	private function _hash_get($key)
	{
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				if (!is_file($this->_file_dir . "/" . $key))
				{
					return;
				}
				
				$result = file_get_contents($this->_file_dir . "/" . $key);
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				$_dba = dba_open($this->_dba_file, "r", $this->_dba_type);
				$result = dba_fetch($key, $_dba);
				dba_close($_dba);
				
				if ($result === false)
				{
					return;
				}
			}
			break;
		}
		
		/* Распоковать строку */
		if ($this->_compress_enable === true)
		{
			$result = gzdecode($result);
		}
		
		return $result;
	}
	
	/**
	 * Добавить ключ-значение в словарь
	 * 
	 * @param string $key
	 * @param string $value
	 */
	private function _hash_set($key, $value)
	{
		/* Сжимать данные */
		if ($this->_compress_enable === true)
		{
			$value = gzencode($value, $this->_compress_level);
		}
		
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				file_put_contents($this->_file_dir . "/" . $key, $value);
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				$this->_dba_file_optimize();
				
				$_dba = dba_open($this->_dba_file, "w", $this->_dba_type);
				dba_replace($key, $value, $_dba);
				dba_close($_dba);
			}
			break;
		}
	}
	
	/**
	 * Удалить ключ-значение из словаря
	 * 
	 * @param string $key
	 */
	private function _hash_delete($key)
	{
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				if (is_file($this->_file_dir . "/" . $key))
				{
					unlink($this->_file_dir . "/" . $key);
				}
			}
			break;

			/* Тип «dba» */
			case "dba":
			{
				$_dba = dba_open($this->_dba_file, "w", $this->_dba_type);
				dba_delete($key, $_dba);
				dba_close($_dba);
			}
			break;
		}
	}
	
	/**
	 * Оптимизация dba файла
	 */
	private function _dba_file_optimize()
	{
		if (filesize($this->_dba_file) > ($this->_dba_file_size_optimize * 1048576))
		{
			$_dba = dba_open($this->_dba_file, "w", $this->_dba_type);
			dba_optimize($_dba);
			dba_close($_dba);
		}
	}
}
?>