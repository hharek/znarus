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
				$_dba = dba_open($this->_dba_file, "r", $this->_dba_type);
				$result = dba_exists($this->_key($identified), $_dba);
				dba_close($_dba);
				
				return $result;
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
		$result = null;
				
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				if (!is_file($this->_file_dir . "/" . $this->_key($identified)))
				{
					return;
				}
				
				$result = file_get_contents($this->_file_dir . "/" . $this->_key($identified));
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				$_dba = dba_open($this->_dba_file, "r", $this->_dba_type);
				$result = dba_fetch($this->_key($identified), $_dba);
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
		
		return json_decode($result, true);
	}

	/**
	 * Добавить
	 * 
	 * @param string $identified
	 * @param mixed $data
	 */
	public function set($identified, $data)
	{
		/* Данные переводим в строку json */
		$data = json_encode($data);
		
		/* Сжимать данные */
		if ($this->_compress_enable === true)
		{
			$data = gzencode($data, $this->_compress_level);
		}
		
		switch ($this->_type)
		{
			/* Тип «file» */
			case "file":
			{
				file_put_contents($this->_file_dir . "/" . $this->_key($identified), $data);
			}
			break;
		
			/* Тип «dba» */
			case "dba":
			{
				$_dba = dba_open($this->_dba_file, "w", $this->_dba_type);
				dba_replace($this->_key($identified), $data, $_dba);
				dba_close($_dba);
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
				$_dba = dba_open($this->_dba_file, "w", $this->_dba_type);
				dba_delete($this->_key($identified), $_dba);
				dba_close($_dba);
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