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
	 * Ресурс dba
	 * 
	 * @var resource
	 */
	private $_dba;

	/**
	 * Конструктор
	 * 
	 * @param string $salt
	 * @param string $type (file|dba)
	 * @param mixed $param
	 */
	public function __construct($salt, $type, $param)
	{
		/* Соль */
		$this->_salt = (string)$salt;
		
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
			$dba = $param;
			
			if(get_resource_type($dba) !== "dba")
			{
				throw new Exception("Параметры заданые неверно. Не является ресурсом «dba»");
			}

			$this->_dba = $dba;
		}
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
		
		return array_reverse(unserialize($result));
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
		
		return unserialize($result);
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
			if ($this->_hash_get($this->_key($identified, end($date_all))) === serialize($data))
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
		$this->_hash_set($this->_key($identified), serialize($date_all));
		$this->_hash_set($this->_key($identified, $date), serialize($data));
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
		if ($date === null)
		{
			return md5($this->_prefix . $identified . $this->_salt);
		}
		elseif ($date !== null)
		{
			return md5($this->_prefix . $identified . date($this->_date_format, strtotime($date)) . $this->_salt);
		}
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
				return dba_exists($key, $this->_dba);
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
				if (is_file($this->_file_dir . "/" . $key))
				{
					return file_get_contents($this->_file_dir . "/" . $key);
				}
				else
				{
					return;
				}
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				$result = dba_fetch($key, $this->_dba);
				if ($result !== false)
				{
					return $result;
				}
				else
				{
					return;
				}
			}
			break;
		}
	}
	
	/**
	 * Добавить ключ-значение в словарь
	 * 
	 * @param string $key
	 * @param string $value
	 */
	private function _hash_set($key, $value)
	{
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
				dba_replace($key, $value, $this->_dba);
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
				dba_delete($key, $this->_dba);
			}
			break;
		}
	}
}
?>