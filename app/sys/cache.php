<?php
/**
 * Класс для работы с кэшом
 */
class _Cache
{
	/**
	 * Включено кэширование
	 * 
	 * @var bool
	 */
	private $_enable = true;

	/**
	 * Тип хранилища кэша (off|memcache|file|dba)
	 * 
	 * @var string
	 */
	private $_type;
	
	/**
	 * Наименование
	 * 
	 * @var string
	 */
	private $_name = "";
	
	/**
	 * Соль для кэша
	 * 
	 * @var string
	 */
	private $_salt = "";
	
	/**
	 * Указатель на файл с ключами
	 * 
	 * @var resource
	 */
	private $_key_log_handle;
	
	/**
	 * Папка с кэшом (если тип file)
	 * 
	 * @var string
	 */
	private $_file_dir;
	
	/**
	 * Объект Memcache или Memcached
	 * 
	 * @var Memcache | Memcached
	 */
	private $_memcache_obj;
	
	/**
	 * Путь к dba файлу
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
	 * Конструктор
	 * 
	 * @param string $name
	 * @param string $salt
	 * @param string $type (off|memcache|file|dba)
	 * @param mixed $param
	 */
	public function __construct ($name = "my", $salt = "", $key_log_file = "key.log", $type = "off", $param = null)
	{
		/* Наименование */
		$this->_check_str($name, "Наименование кэша");
		$this->_name = $name;
		
		/* Соль */
		$this->_salt = trim((string) $salt);
		
		/* Файл с ключами */
		if ($type !== "off")
		{
			$this->_key_log_handle = fopen($key_log_file, "a+");
		}
		
		/* Тип */
		if (!in_array($type, ["off", "memcache", "file", "dba"]))
		{
			throw new Exception("Тип кэширования задан неверно. Допустимые значения (off, memcache, file, dba).");
		}
		$this->_type = $type;
		
		/* Параметры */
		if($type !== "off" and $param === null)
		{
			throw new Exception("Не заданы параметры.");
		}
		
		switch ($type)
		{
			case "file":
			{
				$this->_file_dir_check($param);
				$this->_file_dir = realpath($param);
			}
			break;
		
			case "memcache":
			{
				$this->_memcache_obj_check($param);
				$this->_memcache_obj = $param;
			}
			break;
		
			case "dba":
			{
				$this->_dba_param($param);
			}
			break;
		}
	}
	
	/**
	 * Деструктор
	 */
	public function __destruct()
	{
		if ($this->_type !== "off" and $this->_enable === true)
		{
			fclose($this->_key_log_handle);
		}
	}

	/**
	 * Включить кэширование
	 */
	public function on()
	{
		$this->_enable = true;
	}
	
	/**
	 * Выключить кэширование
	 */
	public function off()
	{
		$this->_enable = false;
	}

	/**
	 * Проверить на наличие
	 * 
	 * @param string $identified
	 * @return bool
	 */
	public function is ($identified)
	{
		return $this->_hash_is($this->_key_cache($identified));
	}

	/**
	 * Получить кэш
	 * 
	 * @param string $identified
	 * @return mixed
	 */
	public function get ($identified)
	{
		/* Данные по кэшу */
		$cache = $this->_hash_get($this->_key_cache($identified));
		if ($cache === null)
		{
			return;
		}
		$cache = json_decode($cache, true);
		
		/* Время хранения истекло */
		if ($cache['date'] < time())
		{
			return;
		}
		
		return $cache['data'];
	}
	
	/**
	 * Добавить кэш
	 * 
	 * @param string $identified
	 * @param mixed $data
	 * @param string | array $tag
	 * @param string $expire
	 */
	public function set ($identified, $data, $tag = null, $expire = "+1 month")
	{
		/* Проверка */
		$this->_check_str($identified, "Идентификатор кэша");
		
		if ($data === null)
		{
			throw new Exception("Нельзя указать данные как NULL.");
		}
		
		if ($tag !== null)
		{
			if (!is_array($tag))
			{
				$tag = [$tag];
			}
		}
		else
		{
			$tag = [];
		}
		
		foreach ($tag as $val)
		{
			$this->_check_str($val, "Идентификатор тэга");
		}
		
		$expire = trim($expire);
		$expire = strtotime($expire);
		if ($expire === false)
		{
			throw new Exception("Время хранения кэша указано неверно.");
		}
		
		/* Подготовить данные */
		$value = 
		[
			"identified" => $identified,
			"data" => $data,
			"tag" => $tag,
			"date" => $expire
		];
		$value = json_encode($value);
		
		/* Добавить кэш */
		$this->_hash_set($this->_key_cache($identified), $value);
		
		/* Добавить наименование кэша в файл ключей */
		if ($this->_type !== "off" and $this->_enable === true)
		{
			fwrite($this->_key_log_handle, "cache_" . $identified . "\n");
		}
		
		/* Добавить тэги */
		if (!empty($tag))
		{
			foreach ($tag as $tag_identified)
			{
				$this->_add_to_tag($tag_identified, $identified);
				
				/* Добавить наименование тэга в файл ключей */
				if ($this->_type !== "off" and $this->_enable === true)
				{
					fwrite($this->_key_log_handle, "tag_" . $tag_identified . "\n");
				}
			}
		}
	}
	
	/**
	 * Удалить кэш
	 * 
	 * @param string $identified
	 */
	public function delete ($identified)
	{
		/* Данные по кэшу */
		$cache = $this->_hash_get($this->_key_cache($identified));
		if ($cache === null)
		{
			return;
		}
		$cache = json_decode($cache, true);
		
		/* Удалить кэш из тэгов */
		foreach ($cache['tag'] as $tag_identified)
		{
			$this->_delete_from_tag($tag_identified, $identified);
		}
		
		/* Удалить кэш */
		$this->_hash_delete($this->_key_cache($identified));
	}
	
	/**
	 * Удалить тэг
	 * 
	 * @param mixed $tags
	 */
	public function delete_tag ($tags)
	{
		if (is_string($tags))
		{
			$tags = [$tags];
		}
		
		foreach ($tags as $tag_identified)
		{
			/* Данные по тэгу */
			$tag = $this->_hash_get($this->_key_tag($tag_identified));
			if ($tag === null)
			{
				break;
			}
			$tag = json_decode($tag, true);

			/* Удалить кэши связанные с тэгом */
			foreach ($tag['cache'] as $cache_identified)
			{
				$this->delete($cache_identified);
			}
		}
	}
	
	/**
	 * Получить тэги по кэшу
	 * 
	 * @param string $identified
	 */
	public function get_tag($identified)
	{
		$cache = $this->_hash_get($this->_key_cache($identified));
		if ($cache === null)
		{
			return;
		}
		$cache = json_decode($cache, true);
		
		return $cache['tag'];
	}

	/**
	 * Удалить весь кэш
	 */
	public function truncate()
	{
		/* Режим «off» */
		if ($this->_type === "off" or $this->_enable === false)
		{
			return;
		}
		
		/* Все ключи */
		$key_all = $this->get_key_all();
		
		/* Удалить тэги */
		$tag_all = $key_all['tag'];
		foreach ($tag_all as $val)
		{
			$this->_hash_delete($this->_key_tag($val));
		}
		
		/* Удалить кэши */
		$cache_all = $key_all['cache'];
		foreach ($cache_all as $val)
		{
			$this->_hash_delete($this->_key_cache($val));
		}
		
		/* Очистить файл с ключами */
		ftruncate($this->_key_log_handle, 0);
	}
	
	/**
	 * Информация по кэшу
	 * 
	 * @return array
	 */
	public function info()
	{
		/* Включить чтобы отобразить ключи правильно */
		$enable = true;
		if ($this->_enable === false)
		{
			$enable = false;
			$this->on();
		}
		
		/* Общие сведения */
		$info = "";
		
		/* Все кэши и тэги */
		$key_all = $this->get_key_all();
		$cache_all = $key_all['cache']; 
		asort($cache_all);
		$tag_all = $key_all['tag'];
		asort($tag_all);
		
		/* Расчитать размер */
		$size = 0;
		foreach ($tag_all as $tag_identified)
		{
			$size += $this->_hash_size($this->_key_tag($tag_identified));
		}
		
		foreach ($cache_all as $cache_identified)
		{
			$size += $this->_hash_size($this->_key_cache($cache_identified));
		}
		
		$info .= "--------------- Общее ---------------\n";
		$info .= "- Количество кэшей: " . count($cache_all) . "\n";
		$info .= "- Количество тэгов: " . count($tag_all) . "\n";
		$info .= "- Размер: {$size}\n";
		
		/* Тэги */
		if (!empty($tag_all))
		{
			$info .= "\n--------------- Кэши ----------------\n";
			foreach ($tag_all as $tag_identified)
			{
				$info .= "- {$tag_identified}\n";
				
				/* Кэши */
				$cache_tag = $this->_hash_get($this->_key_tag($tag_identified));
				if ($cache_tag !== null)
				{
					$cache_tag = json_decode($cache_tag, true)['cache'];
					foreach ($cache_tag as $cache_identified)
					{
						$info .= "\t- {$cache_identified}\n";
					}
				}
			}
		}
		
		/* Кэши без тегов */
		$cache_tag_no = [];
		foreach ($cache_all as $cache_identified)
		{
			$cache = $this->_hash_get($this->_key_cache($cache_identified));
			if ($cache !== null)
			{
				$cache = json_decode($cache, true);
				if (empty($cache['tag']))
				{
					$cache_tag_no[] = $cache_identified;
				}
			}
		}
		if (!empty($cache_tag_no))
		{
			$info .= "\n---------- Кэши без тэгов -----------\n";
			foreach ($cache_tag_no as $cache_identified)
			{
				$info .= "- {$cache_identified}\n";
			}
		}
		
		/* Выключить если был включён */
		if ($enable === false)
		{
			$this->off();
		}
		
		return $info;
	}
	
	/**
	 * Получить все ключи
	 * 
	 * @return array
	 */
	public function get_key_all()
	{
		/* Если отключен */
		if ($this->_type === "off" or $this->_enable === false)
		{
			return 
			[
				"cache" => [],
				"tag" => []
			];
		}
		
		/* Кэши и тэги */
		$cache = []; $tag = [];
		
		/* Чтение из файла */
		fseek($this->_key_log_handle, 0);
		while (($key = fgets($this->_key_log_handle)) !== false) 
		{
			$key = trim($key);
			
			/* Кэш */
			if (substr($key, 0, 6) === "cache_")
			{
				$identified = substr($key, 6);
				if (!in_array($identified, $cache) and self::_hash_is($this->_key_cache($identified)))
				{
					$cache[] = $identified;
				}
			}
			/* Тэг */
			elseif (substr($key, 0, 4) === "tag_")
			{
				$identified = substr($key, 4);
				if (!in_array($identified, $tag) and self::_hash_is($this->_key_tag($identified)))
				{
					$tag[] = $identified;
				}
			}
		}
	
		/* Очистить и создать новый файл с ключами */
		ftruncate($this->_key_log_handle, 0);
		fseek($this->_key_log_handle, 0);
		
		foreach ($cache as $v)
		{
			fwrite($this->_key_log_handle, "cache_" . $v . "\n");
		}
		
		foreach ($tag as $v)
		{
			fwrite($this->_key_log_handle, "tag_" . $v . "\n");
		}
	
		/* Возвращаем кэшы и тэгами */
		return 
		[
			"cache" => $cache,
			"tag" => $tag
		];
	}
	
	/**
	 * Проверка строки на недопустимые символы
	 * 
	 * @param string $str
	 * @param string $name
	 */
	private function _check_str($str, $name)
	{
		$str = trim((string)$str);
		
		if ($str === "")
		{
			throw new Exception($name . " не задан.");
		}
		
		if (ctype_alnum(str_replace("_", "", $str)) === false)
		{
			throw new Exception($name . " задан неверно. Допускаются символы: a-z,0-9,«_» .");
		}	
	}
	
	/**
	 * Получить наименование ключа для кэша
	 * 
	 * @param string $identified
	 * @return string
	 */
	private function _key_cache($identified)
	{
		if ($this->_salt !== "")
		{
			return md5($this->_name . $this->_salt) . "_" . md5("cache" . $this->_salt) . "_" . md5($identified . $this->_salt);
		}
		else
		{
			return $this->_name . "_cache_" . $identified;
		}
	}
	
	/**
	 * Получить наименование ключа для тэга
	 * 
	 * @param string $identified
	 * @return string
	 */
	private function _key_tag($identified)
	{
		if ($this->_salt !== "")
		{
			return md5($this->_name . $this->_salt) . "_" . md5("tag" . $this->_salt) . "_" . md5($identified . $this->_salt);
		}
		else
		{
			return $this->_name . "_tag_" . $identified;
		}
	}
	
	/**
	 * Проверить на существование ключа в хранилище
	 * 
	 * @param string $key
	 * @return bool
	 */
	private function _hash_is($key)
	{
		/* Отключен */
		if ($this->_type === "off" or $this->_enable === false)
		{
			return false;
		}
		
		switch ($this->_type)
		{
			/* Тип «memcache» */
			case "memcache":
			{
				$result = $this->_memcache_obj->get($key);
				if ($result !== false)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			break;
			
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
	 * Получить значение ключа в хранилище
	 * 
	 * @param string $key
	 * @return string
	 */
	private function _hash_get($key)
	{
		/* Отключен */
		if ($this->_type === "off" or $this->_enable === false)
		{
			return;
		}
		
		switch ($this->_type)
		{
			/* Тип «memcache» */
			case "memcache":
			{
				$result = $this->_memcache_obj->get($key);
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
				$_dba = dba_open($this->_dba_file, "r", $this->_dba_type);
				$result = dba_fetch($key, $_dba);
				dba_close($_dba);
				
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
	 * Добавить ключ-значение в хранилище
	 * 
	 * @param string $key
	 * @param string $value
	 */
	private function _hash_set($key, $value)
	{
		/* Отключен */
		if ($this->_type === "off" or $this->_enable === false)
		{
			return;
		}
		
		switch ($this->_type)
		{
			/* Тип «memcache» */
			case "memcache":
			{
				$this->_memcache_obj->set($key, $value);
			}
			break;
		
			/* Тип «file» */
			case "file":
			{
				file_put_contents($this->_file_dir . "/" . $key, $value);
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				$_dba = dba_open($this->_dba_file, "w", $this->_dba_type);
				dba_replace($key, $value, $_dba);
				dba_close($_dba);
			}
			break;
		}
	}
	
	/**
	 * Удалить ключ-значение из хранилища
	 * 
	 * @param string $key
	 */
	private function _hash_delete($key)
	{
		/* Отключен */
		if ($this->_type === "off" or $this->_enable === false)
		{
			return;
		}
		
		switch ($this->_type)
		{
			/* Тип «memcache» */
			case "memcache":
			{
				$this->_memcache_obj->delete($key);
			}
			break;
		
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
	 * Определить размер ключа в байтах
	 * 
	 * @param string $key
	 * @return int
	 */
	private function _hash_size($key)
	{
		return strlen($this->_hash_get($key));
	}
	
	/**
	 * Добавить кэш в тэг
	 * 
	 * @param string $tag_identified
	 * @param string $cache_identified
	 */
	private function _add_to_tag($tag_identified, $cache_identified)
	{
		$tag = 
		[
			"identified" => $tag_identified,
			"cache" => []
		];
		
		if ($this->_hash_is($this->_key_tag($tag_identified)) === true)
		{
			$tag['cache'] = json_decode($this->_hash_get($this->_key_tag($tag_identified)), true)['cache'];
		}
		
		if (!in_array($cache_identified, $tag['cache']))
		{
			$tag['cache'][] = $cache_identified;
		}
		
		$this->_hash_set($this->_key_tag($tag_identified), json_encode($tag));
	}

	/**
	 * Удалить кэш из тэга
	 * 
	 * @param string $tag_identified
	 * @param string $cache_identified
	 */
	private function _delete_from_tag($tag_identified, $cache_identified)
	{
		if ($this->_hash_is($this->_key_tag($tag_identified)) === false)
		{
			return;
		}
		
		$tag = json_decode($this->_hash_get($this->_key_tag($tag_identified)), true);
		
		$tag['cache'] = array_diff($tag['cache'], [$cache_identified]);
		
		if (!empty($tag['cache']))
		{
			$this->_hash_set($this->_key_tag($tag_identified), json_encode($tag));
		}
		else
		{
			$this->_hash_delete($this->_key_tag($tag_identified));
		}
	}

	/**
	 * Назначить папку для кэша (если тип file)
	 * 
	 * @param string $dir
	 */
	private function _file_dir_check ($dir)
	{
		if (empty($dir))
		{
			throw new Exception("Параметры заданые неверно. Укажите папку для кэширования.");
		}
		
		if (!is_dir($dir))
		{
			throw new Exception("Параметры заданые неверно. Папки «{$dir}» не существует.");
		}
	}
	
	/**
	 * Назначить объект memcache
	 * 
	 * @param object $memcache_obj
	 */
	private function _memcache_obj_check ($memcache_obj)
	{
		if (!in_array(get_class($memcache_obj), ["Memcache", "Memcached"]))
		{
			throw new Exception("Параметры заданые неверно. Не является объектом класса «Memcache» или «Memcached».");
		}
	}
	
	/**
	 * Проверям и назначаем параметры
	 * 
	 * @param mixed $param
	 */
	private function _dba_param ($param)
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
}
?>