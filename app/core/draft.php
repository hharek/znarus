<?php
/**
 * Черновик
 */
class _Draft
{
	/**
	 * Префикс для ключей
	 * 
	 * @var type 
	 */
	private $_prefix = "draft_";

	/**
	 * Соль для наименования ключей
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
	 * Проверка на существование
	 * 
	 * @param string $identified
	 * @return bool
	 */
	public function is($identified)
	{
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				return is_file($this->_file_dir . "/" . $this->_key($identified));
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				return dba_exists($this->_key($identified), $this->_dba);
			}
			break;
		}
	}

	/**
	 * Получить черновик 
	 * 
	 * @param string $identified
	 * @return array
	 */
	public function get($identified)
	{
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				if (is_file($this->_file_dir . "/" . $this->_key($identified)))
				{
					return unserialize(file_get_contents($this->_file_dir . "/" . $this->_key($identified)));
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
				$result = dba_fetch($this->_key($identified), $this->_dba);
				if ($result !== false)
				{
					return unserialize($result);
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
	 * Добавить
	 * 
	 * @param string $identified
	 * @param mixed $data
	 */
	public function set($identified, $data)
	{
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				file_put_contents($this->_file_dir . "/" . $this->_key($identified), serialize($data));
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				dba_replace($this->_key($identified), serialize($data), $this->_dba);
			}
			break;
		}
	}

	/**
	 * Удалить 
	 * 
	 * @param string $identified
	 */
	public function delete($identified)
	{
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				if (is_file($this->_file_dir . "/" . $this->_key($identified)))
				{
					unlink($this->_file_dir . "/" . $this->_key($identified));
				}
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				dba_delete($this->_key($identified), $this->_dba);
			}
			break;
		}
	}

	/**
	 * Получить имя ключа
	 * 
	 * @param string $identified
	 * @return string
	 */
	private function _key($identified)
	{
		return md5($this->_prefix . $identified . $this->_salt);
	}
}
?>