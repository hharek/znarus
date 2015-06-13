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
	 * Тип хранилища кэша (off|file|memcache|dba|mixed)
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
	 * Папка с кэшом (если тип file)
	 * 
	 * @var string
	 */
	private $_file_dir;
	
	/**
	 * Объект memcache
	 * 
	 * @var Memcache
	 */
	private $_memcache_obj;

	/**
	 * Ресурс dba
	 * 
	 * @var resource
	 */
	private $_dba;
	
	/**
	 * Максимальный размер строки в байтах для хранения в memcache (20 килобит = 20480) если тип «mixed»
	 * 
	 * @var int
	 */
	private $_mixed_memcache_size = 20480;

	/**
	 * Конструктор
	 * 
	 * @param string $name
	 * @param string $salt
	 * @param string $type (off|file|memcache|dba|mixed)
	 * @param mixed $param
	 */
	public function __construct ($name = "my", $salt = "", $type = "off", $param = null)
	{
		/* Наименование */
		$this->_check_str($name, "Наименование кэша");
		$this->_name = $name;
		
		/* Соль */
		$this->_salt = trim((string) $salt);
		
		/* Тип */
		if (!in_array($type, ["off", "file", "memcache", "dba", "mixed"]))
		{
			throw new Exception("Тип кэширования задан неверно. Допустимые значения (off, file, memcache, dba, mixed).");
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
				$this->_file_dir($param);
			}
			break;
		
			case "memcache":
			{
				$this->_memcache_obj($param);
			}
			break;
		
			case "dba":
			{
				$this->_dba($param);
			}
			break;
		
			case "mixed":
			{
				$this->_memcache_obj($param[0]);
				$this->_dba($param[1]);
			}
			break;
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
		$cache = unserialize($cache);
		
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
		$value = serialize($value);
		
		/* Добавить кэш */
		$this->_hash_set($this->_key_cache($identified), $value);
		
		/* Добавить тэги */
		if (!empty($tag))
		{
			foreach ($tag as $tag_identified)
			{
				$this->_add_to_tag($tag_identified, $identified);
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
		$cache = unserialize($cache);
		
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
	 * @param string $identified
	 */
	public function delete_tag ($identified)
	{
		/* Данные по тэгу */
		$tag = $this->_hash_get($this->_key_tag($identified));
		if ($tag === null)
		{
			return;
		}
		$tag = unserialize($tag);
		
		/* Удалить кэши связанные с тэгом */
		foreach ($tag['cache'] as $cache_identified)
		{
			$this->delete($cache_identified);
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
		$cache = unserialize($cache);
		
		return $cache['tag'];
	}

	/**
	 * Удалить весь кэш
	 */
	public function truncate()
	{
		/* Режим «off» */
		if ($this->_type === "off")
		{
			return;
		}
		
		/* Удалить тэги */
		$tag_all = $this->get_key_all("tag");
		foreach ($tag_all as $val)
		{
			$this->_hash_delete($this->_key_tag($val));
		}
		
		/* Удалить кэши */
		$cache_all = $this->get_key_all("cache");
		foreach ($cache_all as $val)
		{
			$this->_hash_delete($this->_key_cache($val));
		}
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
		$cache_all = $this->get_key_all("cache"); 
		asort($cache_all);
		$tag_all = $this->get_key_all("tag");
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
					$cache_tag = unserialize($cache_tag)['cache'];
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
				$cache = unserialize($cache);
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
	 * @param string $type
	 * @return array
	 */
	public function get_key_all($type)
	{
		/* Если отключен */
		if ($this->_type === "off")
		{
			return [];
		}
		
		/* Проверка */
		if (!in_array($type, ["cache","tag"]))
		{
			throw new Exception("Необходимо указать «cache» или «tag».");
		}
		
		/* Префикс */
		$prefix = ""; 
		if ($this->_salt !== "")
		{
			$prefix = md5($this->_name . $this->_salt) . "_" . md5($type . $this->_salt);
		}
		else
		{
			$prefix = $this->_name . "_" . $type . "_";
		}
		
		/* Все ключи в хранилище */
		$key_all_storage = $this->get_key_all_storage();
		$key_all = [];
		foreach ($key_all_storage as $key)
		{
			if (substr($key, 0, strlen($prefix)) === $prefix)
			{
				$key_all[] = unserialize($this->_hash_get($key))['identified'];
			}
		}
		
		return $key_all;
	}
	
	/**
	 * Показать все ключи хранилища по типу
	 * 
	 * @return array
	 */
	public function get_key_all_storage($type = null)
	{
		/* Тип хранилища */
		if ($type === null)
		{
			$type = $this->_type;
		}
		
		/* Все ключи */
		switch ($type)
		{
			case "off":
			{
			    throw new Exception("Невозможно получить ключи хранилища для типа «off».");
			}
			break;
			
			case "file":
			{
				$scandir = scandir($this->_file_dir);
				$scandir = array_diff($scandir, [".", ".."]);
				
				return $scandir;
			}
			break;
			
			case "memcache":
			{
				$server_slabs = $this->_memcache_obj->getextendedstats("slabs");

				$item_all = [];
				foreach ($server_slabs as $server => $slabs)
				{
					foreach ($slabs as $slab_id => $data)
					{
						$cachedump = $this->_memcache_obj->getextendedstats("cachedump", (int)$slab_id);

						foreach ($cachedump as $val)
						{
							if (!is_array($val))
							{
								continue;
							}

							foreach ($val as  $item_key => $item_val)
							{
								$item_all[] = $item_key;
							}
						}
					}
				}
				
				return $item_all;
			}
			break;
			
			case "dba":
			{
				$key_all = [];
				
				$key = dba_firstkey($this->_dba); 
				while ($key !== false)
				{
					$key_all[] = $key;
					
					$key = dba_nextkey($this->_dba);
				}
				
				return $key_all;
			}
			break;	
			
			case "mixed":
			{
				return array_merge(self::get_key_all_storage("memcache"), self::get_key_all_storage("dba")) ;
			}
			break;
		}
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
			/* Тип «file» */
			case "file":
			{
				return is_file($this->_file_dir . "/" . $key);
			}
			break;
		
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
			
			/* Тип «dba» */
			case "dba":
			{
				return dba_exists($key, $this->_dba);
			}
			break;
		
			/* Тип «mixed» */
			case "mixed":
			{
				$result = $this->_memcache_obj->get($key);
				if ($result !== false)
				{
					return true;
				}
				
				$result = dba_exists($key, $this->_dba);
				if ($result !== false)
				{
					return true;
				}
				
				return false;
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
			
			/* Тип «mixed» */
			case "mixed":
			{
				$result = $this->_memcache_obj->get($key);
				if ($result !== false)
				{
					return $result;
				}
				
				$result = dba_fetch($key, $this->_dba);
				if ($result !== false)
				{
					return $result;
				}
				
				return;
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
			/* Тип «file» */
			case "file":
			{
				file_put_contents($this->_file_dir . "/" . $key, $value);
			}
			break;
		
			/* Тип «memcache» */
			case "memcache":
			{
				$this->_memcache_obj->set($key, $value);
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				dba_replace($key, $value, $this->_dba);
			}
			break;
		
			/* Тип «mixed» */
			case "mixed":
			{
				/* Если строка маленькая */
				if (strlen($value) < $this->_mixed_memcache_size)
				{
					/* Удаляем из dba, если строки уменьшился */
					$result = dba_exists($key, $this->_dba);
					if ($result !== false)
					{
						dba_delete($key, $this->_dba);
					}
					
					/* Размещаем в memcache */
					$this->_memcache_obj->set($key, $value);
				}
				/* Если строка большая */
				elseif (strlen($value) >= $this->_mixed_memcache_size)
				{
					/* Удаляем из memcache, если строка увеличилась */
					$result = $this->_memcache_obj->get($key);
					if ($result !== false)
					{
						$this->_memcache_obj->delete($key);
					}
					
					/* Размещаем в dba */
					dba_replace($key, $value, $this->_dba);
				}
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
			/* Тип «file» */
			case "file":
			{
				if (is_file($this->_file_dir . "/" . $key))
				{
					unlink($this->_file_dir . "/" . $key);
				}
			}
			break;
			
			/* Тип «memcache» */
			case "memcache":
			{
				$this->_memcache_obj->delete($key);
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				dba_delete($key, $this->_dba);
			}
			break;
		
			/* Тип «mixed» */
			case "mixed":
			{
				$this->_memcache_obj->delete($key);
				dba_delete($key, $this->_dba);
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
			$tag['cache'] = unserialize($this->_hash_get($this->_key_tag($tag_identified)))['cache'];
		}
		
		if (!in_array($cache_identified, $tag['cache']))
		{
			$tag['cache'][] = $cache_identified;
		}
		
		$this->_hash_set($this->_key_tag($tag_identified), serialize($tag));
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
		
		$tag = unserialize($this->_hash_get($this->_key_tag($tag_identified)));
		
		$tag['cache'] = array_diff($tag['cache'], [$cache_identified]);
		
		if (!empty($tag['cache']))
		{
			$this->_hash_set($this->_key_tag($tag_identified), serialize($tag));
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
	private function _file_dir ($dir)
	{
		if (empty($dir))
		{
			throw new Exception("Параметры заданые неверно. Укажите папку для кэширования.");
		}
		
		if (!is_dir($dir))
		{
			throw new Exception("Параметры заданые неверно. Папки «{$dir}» не существует.");
		}
		
		$this->_file_dir = realpath($dir);
	}
	
	/**
	 * Назначить объект memcache
	 * 
	 * @param object $memcache_obj
	 */
	private function _memcache_obj ($memcache_obj)
	{
		if (get_class($memcache_obj) !== "Memcache")
		{
			throw new Exception("Параметры заданые неверно. Не является объектом класса «Memcache»");
		}
		
		$this->_memcache_obj = $memcache_obj;
	}
	
	/**
	 * Назначить ресурс dba
	 * 
	 * @param resource $dba
	 */
	private function _dba($dba)
	{
		if(get_resource_type($dba) !== "dba")
		{
			throw new Exception("Параметры заданые неверно. Не является ресурсом «dba»");
		}
		
		$this->_dba = $dba;
	}
}
?>