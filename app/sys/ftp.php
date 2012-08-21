<?php
/**
 * Класс для работы с файлами по протоколу FTP
 * 
 * @category	Networking
 * @author		Sergeev Denis <hharek@yandex.ru>
 * @copyright	2011 Sergeev Denis
 * @license		https://github.com/hharek/zn_ftp/wiki/MIT-License MIT License
 * @version		0.2.3
 * @link		https://github.com/hharek/zn_ftp/
 */
class ZN_FTP
{

	/**
	 * Дескриптор подключения
	 * 
	 * @var resource
	 */
	private $_conn_id;

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
	 * Путь по умолчанию (для относительных путей)
	 * 
	 * @var string
	 */
	private $_path;

	/**
	 * Порт
	 * 
	 * @var int
	 */
	private $_port;

	/**
	 * Использоваталь ssl
	 * 
	 * @var bool
	 */
	private $_ssl;

	/**
	 * Таймаут
	 * 
	 * @var int
	 */
	private $_timeout = 30;

	/**
	 * Состояние chroot
	 * 
	 * @var bool
	 */
	private $_chroot = false;

	/**
	 * Временные файлы для zip архива
	 * 
	 * @var array
	 */
	private $_zip_tmp_file = array();

	/**
	 * Назначить данные для соединения
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $path
	 * @param int $port
	 * @param bool $ssl
	 * @return bool
	 */
	public function __construct($host, $user, $pass, $path="/", $port=21, $ssl=false)
	{
		/* Проверка */
		$host = trim($host);
		if (empty($host))
		{
			throw new Exception("FTP-хост не задан.", 11);
		}

		$user = trim($user);
		if (empty($user))
		{
			throw new Exception("FTP-пользователь не задан.", 12);
		}

		/* Корневой путь */
		$path = trim($path);
		if (empty($path))
		{
			throw new Exception("Корневая папка для FTP-сервера не задана.", 13);
		}
		if (mb_substr($path, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Корневая папка \"{$path}\" для FTP-сервера задана неверно.", 14);
		}
		$path = $this->_normalize_path($path);

		$port = (int) $port;
		if (empty($port))
		{
			throw new Exception("FTP-порт не задан.", 15);
		}

		$ssl = (boolean) $ssl;

		/* Назначить */
		$this->_host = $host;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_path = $path;
		$this->_port = $port;
		$this->_ssl = $ssl;

		/* Одно соединение для несколько клонов */
		$this->_conn_id = &$this->_conn_id;
		
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
	 * Подключиться
	 * 
	 * @return bool
	 */
	public function connect()
	{
		if (empty($this->_conn_id))
		{
			/* Подключение к хосту */
			if ($this->_ssl == false)
			{
				$this->_conn_id = @ftp_connect($this->_host, $this->_port, $this->_timeout);
			}
			else
			{
				$this->_conn_id = @ftp_ssl_connect($this->_host, $this->_port, $this->_timeout);
			}

			if (!$this->_conn_id)
			{
				$error = error_get_last();
				throw new Exception("Не удалось установить соединение с FTP-сервером. " . $error['message'], 21);
			}

			/* Назначить таймаут */
			if ($this->_timeout != 30)
			{
				@ftp_set_option($this->_conn_id, FTP_TIMEOUT_SEC, $this->_timeout);
			}

			/* Идентификация */
			$login = @ftp_login($this->_conn_id, $this->_user, $this->_pass);
			if (!$login)
			{
				$error = error_get_last();
				throw new Exception("Логин и пароль для FTP-сервера заданы неверно. " . $error['message'], 22);
			}

			/* Включение пассивного режима */
			if (!@ftp_pasv($this->_conn_id, true))
			{
				$error = error_get_last();
				throw new Exception("Не удалось включить пассивный режим для FTP-сервера. " . $error['message'], 23);
			}

			/* Текущая категория */
			if (!$this->is_dir($this->_path))
			{
				throw new Exception("FTP-папки \"{$this->_path}\" не существует.", 24);
			}
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
		if (!empty($this->_conn_id))
		{
			@ftp_close($this->_conn_id);
		}

		return true;
	}

	/**
	 * Назначить таймаут
	 * 
	 * @param int $timeout
	 * @return bool
	 */
	public function set_timeout($timeout)
	{
		$timeout = (int) $timeout;
		$this->_timeout = $timeout;

		if (!empty($this->_conn_id))
		{
			if ($this->_timeout != 30)
			{
				@ftp_set_option($this->_conn_id, FTP_TIMEOUT_SEC, $this->_timeout);
			}
		}

		return true;
	}

	/**
	 * Папка по умолчанию
	 * 
	 * @param string $path
	 * @return bool
	 */
	public function set_path($path)
	{
		if($path == $this->_path)
		{
			return true;
		}
		
		$path = trim($path);
		if (mb_substr($path, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Наименование FTP-папки \"" . func_get_arg(0) . "\" задано неверно.", 31);
		}

		$path = $this->_normalize_path($path);

		if (!empty($this->_conn_id))
		{
			if (!$this->is_dir($path))
			{
				throw new Exception("FTP-папки \"" . func_get_arg(0) . "\" не существует.", 32);
			}
		}

		$this->_path = $path;

		return true;
	}

	/**
	 * Получить папку по умолчанию
	 * 
	 * @param string $path
	 * @return bool
	 */
	public function get_path()
	{
		return $this->_path;
	}

	/**
	 * Включить chroot
	 * 
	 * @return bool
	 */
	public function chroot_enable()
	{
		$this->_chroot = true;
		return true;
	}

	/**
	 * Отключить chroot
	 * 
	 * @return bool
	 */
	public function chroot_disable()
	{
		$this->_chroot = false;
		return true;
	}

	/**
	 * Проверка на существование файла
	 * 
	 * @param string $file
	 * @return bool
	 */
	public function is_file($file)
	{
		/* Проверка */
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		if ($file == "/")
		{
			return false;
		}

		/* Соединение */
		$this->connect();

		/* Проверка файла */
		$type = $this->_get_type_file($file);
		if ($type == "file")
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Проверка на существование каталога
	 * 
	 * @param string $path
	 * @return bool
	 */
	public function is_dir($path)
	{
		/* Проверка */
		$path = $this->_normalize_path($path);
		$this->_check_chroot($path);

		if ($path == "/")
		{
			return true;
		}

		/* Соединение */
		$this->connect();

		/* Проверка каталога */
		$type = $this->_get_type_file($path);
		if ($type == "dir")
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Список каталогов и файлов в папке
	 * 
	 * @param string $path
	 * @param string $type (all|dir|file)
	 * @param string $ext
	 * @return array
	 */
	public function ls($path, $type="all", $ext="")
	{
		/* Проверка */
		$path = $this->_normalize_path($path);
		$this->_check_chroot($path);

		$type = trim($type);
		if (!in_array($type, array('all', 'file', 'dir')))
		{
			throw new Exception("Тип \"" . func_get_arg(1) . "\" задан неверно. Необходимо указать: (all|file|dir).", 41);
		}
		
		$ext = trim($ext);
		if ($type != "file" and mb_strlen($ext, "UTF-8") > 0)
		{
			throw new Exception("Расширение можно задать только для файлов.", 42);
		}

		if (!empty($ext) and !preg_match("#^[a-zA-Z0-9]{1,5}$#isu", $ext))
		{
			throw new Exception("Расширение \"" . func_get_arg(2) . "\" задано неверно.", 43);
		}

		/* Соединение */
		$this->connect();

		/* Список */
		if (!$this->is_dir($path))
		{
			throw new Exception("FTP-папки \"" . func_get_arg(0) . "\" не существует", 44);
		}

		$ls = array();
		$raw_list = @ftp_rawlist($this->_conn_id, $path);
		if (!empty($raw_list))
		{
			foreach ($raw_list as $val)
			{
				$file_settings = $this->_raw_razbor($val);
				if (empty($file_settings) or $file_settings['name'] == "." or $file_settings['name'] == "..")
				{
					continue;
				}

				switch ($type)
				{
					case "all":
					{
						$ls[] = $file_settings;
					}
					break;

					case "dir":
					{
						if ($file_settings['type'] == "dir")
						{
							$ls[] = $file_settings;
						}
					}
					break;

					case "file":
					{
						if ($file_settings['type'] == "file")
						{
							if (mb_substr($file_settings['name'], mb_strlen($file_settings['name'], "UTF-8") - mb_strlen($ext, "UTF-8"), mb_strlen($ext, "UTF-8"), "UTF-8") == $ext)
							{
								$ls[] = $file_settings;
							}
						}
					}
					break;
				}
			}
		}

		return $ls;
	}

	/**
	 * Получить содержимое файла
	 * 
	 * @param string $file
	 * @return string
	 */
	public function get($file)
	{
		/* Проверка */
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		/* Соединение */
		$this->connect();

		/* Содержимое файла */
		if (!$this->is_file($file))
		{
			throw new Exception("FTP-файла с именем \"" . func_get_arg(0) . "\" не существует.", 51);
		}

		$tmp_file = tmpfile();

		if (!@ftp_fget($this->_conn_id, $tmp_file, $file, FTP_BINARY))
		{
			$error = error_get_last();
			throw new Exception("Не удалось прочитать FTP-файл \"" . func_get_arg(0) . "\". " . $error['message'], 52);
		}

		fseek($tmp_file, 0);
		$content = "";
		while (!feof($tmp_file))
		{
			$content .= fread($tmp_file, 1024);
		}
		fclose($tmp_file);

		return $content;
	}

	/**
	 * Записать строку в файл
	 * 
	 * @param string $file
	 * @param string $content 
	 * @return bool
	 */
	public function put($file, $content)
	{
		/* Проверка */
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		/* Соединение */
		$this->connect();

		/* Проверка папки */
		$file_type = $this->_get_type_file($file);
		if ($file_type == "dir")
		{
			throw new Exception("Невозможно записать строку в FTP-папку.", 61);
		}
		elseif ($file_type == "null")
		{
			$file_ar = explode("/", $file);
			$file_name = array_pop($file_ar);
			if (count($file_ar) != 1)
			{
				$file_up = implode("/", $file_ar);
			}
			else
			{
				$file_up = "/";
			}
			$file_up_type = $this->_get_type_file($file_up);
			if ($file_up_type != "dir")
			{
				throw new Exception("Имя FTP-файла \"" . func_get_arg(0) . "\" задано неверно.", 62);
			}
		}

		/* Записать */
		$tmp_file = tmpfile();
		fwrite($tmp_file, $content);
		fseek($tmp_file, 0);

		if (!@ftp_fput($this->_conn_id, $file, $tmp_file, FTP_BINARY))
		{
			$error = error_get_last();
			throw new Exception("Не удалось записать строку в FTP-файл \"" . func_get_arg(0) . "\". " . $error['message'], 63);
		}
		fclose($tmp_file);

		return true;
	}

	/**
	 * Создать папку
	 * 
	 * @param string $path 
	 * @return bool
	 */
	public function mkdir($path)
	{
		/* Проверка */
		$path = $this->_normalize_path($path);
		$this->_check_chroot($path);

		/* Соединение */
		$this->connect();

		/* Создать папку */
		if (!@ftp_mkdir($this->_conn_id, $path))
		{
			$error = error_get_last();
			throw new Exception("Не удалось создать FTP-папку \"" . func_get_arg(0) . "\". " . $error['message'], 71);
		}

		return true;
	}

	/**
	 * Копировать файлы
	 * 
	 * @param string $source
	 * @param string $dest
	 * @return bool 
	 */
	public function cp($source, $dest)
	{
		/* Проверка */
		$source = $this->_normalize_path($source);
		$this->_check_chroot($source);
		if ($source == "/")
		{
			throw new Exception("FTP-файл источник \"" . func_get_arg(0) . "\" задан неверно.", 81);
		}

		$dest = $this->_normalize_path($dest);
		$this->_check_chroot($dest);

		/* Соединение */
		$this->connect();

		$type_source = $this->_get_type_file($source);
		if($type_source == "null")
		{
			throw new Exception("FTP-файл источник \"" . func_get_arg(0) . "\" задан неверно.", 84);
		}
		$type_dest = $this->_get_type_file($dest);
		
		/* Копирование */
		if ($type_source == "file")
		{
			if ($type_dest == "dir")
			{
				$dest .= "/" . basename($source);
				if ($source == $dest)
				{
					throw new Exception("FTP-файл источник и FTP-файл назначения - это один и тот же файл.", 82);
				}
			}
			$this->_cp_file($source, $dest);
		}
		elseif ($type_source == "dir")
		{
			$dest .= "/" . basename($source);
			if ($source == $dest)
			{
				throw new Exception("FTP-папка источник и FTP-папка назначения - это одна и та же папка.", 83);
			}
			$this->_cp_dir($source, $dest);
		}
		
		return true;
	}

	/**
	 * Перенести или переименовать файл или папку
	 * 
	 * @param string $source
	 * @param string $dest 
	 * @return bool
	 */
	public function mv($source, $dest)
	{
		/* Проверка */
		$source = $this->_normalize_path($source);
		$this->_check_chroot($source);
		if ($source == "/")
		{
			throw new Exception("FTP-файл источник \"" . func_get_arg(0) . "\" задан неверно.", 91);
		}

		$dest = $this->_normalize_path($dest);
		$this->_check_chroot($dest);

		/* Соединение */
		$this->connect();

		$type_dest = $this->_get_type_file($dest);
		if ($type_dest == "dir")
		{
			$dest .= "/" . basename($source);
			if ($source == $dest)
			{
				throw new Exception("FTP-файл источник и FTP-файл назначения - это один и тот же файл.", 92);
			}
		}

		if (!@ftp_rename($this->_conn_id, $source, $dest))
		{
			$error = error_get_last();
			throw new Exception("Не удалось перенести \"" . func_get_arg(0) . "\" в \"" . func_get_arg(1) . "\". " . $error['message'], 93);
		}

		return true;
	}

	/**
	 * Удалить файл или папку
	 * 
	 * @param string $file 
	 * @return bool
	 */
	public function rm($file)
	{
		/* Проверка */
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		/* Соединение */
		$this->connect();

		$type = $this->_get_type_file($file);
		if ($type == "null")
		{
			throw new Exception("FTP-файла с именем \"" . func_get_arg(0) . "\" не существует.", 101);
		}

		/* Удаление */
		if ($type == "file")
		{
			if (!@ftp_delete($this->_conn_id, $file))
			{
				$error = error_get_last();
				throw new Exception("Не удалось удалить FTP-файл \"" . func_get_arg(0) . "\". " . $error['message'], 102);
			}
		}
		elseif ($type == "dir")
		{
			$this->_rm_dir($file);
		}

		return true;
	}

	/**
	 * Устанавливает права доступа к файлу или папке
	 * 
	 * @param string $file
	 * @param int $mode
	 * @param bool $recursion 
	 * @return bool
	 */
	public function chmod($file, $mode, $recursion=true)
	{
		/* Проверка */
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		/* Соединение */
		$this->connect();

		$type = $this->_get_type_file($file);
		if ($type == "null")
		{
			throw new Exception("FTP-файла с именем \"" . func_get_arg(0) . "\" не существует.", 111);
		}

		$mode = (int) $mode;
		$recursion = (bool) $recursion;

		/* Установить права доступа */
		if ($recursion == false or $type == "file")
		{
			if (!@ftp_chmod($this->_conn_id, $mode, $file))
			{
				$error = error_get_last();
				throw new Exception("Не удалось установить права \"" . func_get_arg(1) . "\" на FTP-файл \"" . func_get_arg(0) . "\". " . $error['message'], 112);
			}
		}
		else
		{
			$this->_chmod_dir($file, $mode);
		}

		return true;
	}

	/**
	 * Получить размер файла или папки в байтах
	 * 
	 * @param string $file 
	 * @return int
	 */
	public function size($file)
	{
		/* Проверка */
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		/* Соединение */
		$this->connect();

		$type = $this->_get_type_file($file);
		if ($type == "null")
		{
			throw new Exception("FTP-файла с именем \"" . func_get_arg(0) . "\" не существует.", 121);
		}

		/* Получить размер */
		if ($type == "file")
		{
			$size = $this->_size_file($file);
		}
		elseif ($type == "dir")
		{
			$dir_ar = explode("/", $file);
			$dir_name = array_pop($dir_ar);
			if (count($dir_ar) != 1)
			{
				$dir_up = implode("/", $dir_ar);
			}
			else
			{
				$dir_up = "/";
			}
			$raw_list_up = @ftp_rawlist($this->_conn_id, $dir_up);

			foreach ($raw_list_up as $val)
			{
				$file_settings = $this->_raw_razbor($val);
				if ($file_settings['name'] == $dir_name and $file_settings['type'] == "dir")
				{
					$size = $file_settings['size'];
					break;
				}
			}

			$size += $this->_size_dir($file);
		}

		return $size;
	}

	/**
	 * Загрузить файл на ftp-сервер
	 * 
	 * @param string $file
	 * @param string $ftp_file
	 * @param bool $check_form_upload 
	 * @return bool
	 */
	public function upload($file, $ftp_file, $check_form_upload=false)
	{
		/* Проверка */
		$file = trim($file);
		if (mb_substr($file, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Имя файла \"" . func_get_arg(0) . "\" задано неверно.", 131);
		}
		$file = $this->_normalize_path($file);
		if (!is_file($file))
		{
			throw new Exception("Файла \"" . func_get_arg(0) . "\" не существует.", 132);
		}

		$ftp_file = $this->_normalize_path($ftp_file);
		$this->_check_chroot($ftp_file);

		$ftp_file_ar = explode("/", $ftp_file);
		$ftp_file_name = array_pop($ftp_file_ar);
		if (count($ftp_file_ar) != 1)
		{
			$ftp_file_up = implode("/", $ftp_file_ar);
		}
		else
		{
			$ftp_file_up = "/";
		}

		/* Соединение */
		$this->connect();

		$ftp_file_type_up = $this->_get_type_file($ftp_file_up);
		if ($ftp_file_type_up != "dir")
		{
			throw new Exception("Имя FTP-файла \"" . func_get_arg(1) . "\" задано неверно.", 133);
		}
		if($this->_get_type_file($ftp_file) == "dir")
		{
			$ftp_file = $ftp_file . "/" . basename($file);
		}

		$check_form_upload = (bool) $check_form_upload;

		if ($check_form_upload)
		{
			if (!is_uploaded_file($file))
			{
				throw new Exception("Файл \"" . func_get_arg(0) . "\" загружен не при помощи HTTP POST", 134);
			}
		}

		/* Загрузить */
		$fp = @fopen($file, "rb");
		if ($fp === false)
		{
			$error = error_get_last();
			throw new Exception("Не удалось открыть файл \"" . func_get_arg(0) . "\". " . $error['message'], 135);
		}

		if (!@ftp_fput($this->_conn_id, $ftp_file, $fp, FTP_BINARY))
		{
			$error = error_get_last();
			throw new Exception("Не удалось записать в FTP-файл \"" . func_get_arg(1) . "\". " . $error['message'], 136);
		}
		fclose($fp);

		return true;
	}

	/**
	 * Загрузить папку
	 * 
	 * @param string $dir
	 * @param string $ftp_dir
	 * @return bool
	 */
	public function upload_dir($dir, $ftp_dir)
	{
		/* Проверка папки */
		$dir = trim($dir);
		if (mb_substr($dir, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Наименование папки \"" . func_get_arg(0) . "\" задано неверно.", 141);
		}
		$dir = $this->_normalize_path($dir);
		if (!is_dir($dir))
		{
			throw new Exception("Папки \"" . func_get_arg(0) . "\" не существует.", 142);
		}

		/* Проверка FTP-папки */
		$ftp_dir = $this->_normalize_path($ftp_dir);
		$this->_check_chroot($ftp_dir);

		$ftp_dir_ar = explode("/", $ftp_dir);
		$ftp_dir_name = array_pop($ftp_dir_ar);
		if (count($ftp_dir_ar) != 1)
		{
			$ftp_dir_up = implode("/", $ftp_dir_ar);
		}
		else
		{
			$ftp_dir_up = "/";
		}

		/* Соединение */
		$this->connect();

		$ftp_dir_type_up = $this->_get_type_file($ftp_dir_up);
		if ($ftp_dir_type_up != "dir")
		{
			throw new Exception("Имя FTP-папки \"" . func_get_arg(1) . "\" задано неверно.", 143);
		}

		$ftp_dir_type = $this->_get_type_file($ftp_dir);

		/* Создать папку в случае отсутствия */
		if ($ftp_dir_type == "file")
		{
			throw new Exception("FTP-папка \"" . func_get_arg(1) . "\" является файлом.", 144);
		}
		elseif ($ftp_dir_type == "null")
		{
			if (!@ftp_mkdir($this->_conn_id, $ftp_dir))
			{
				$error = error_get_last();
				throw new Exception("Не удалось создать FTP-папку \"" . func_get_arg(1) . "\". " . $error['message'], 145);
			}
		}
		elseif ($ftp_dir_type == "dir")
		{
			if ($this->_get_type_file($ftp_dir . "/" . basename($dir)) != "null")
			{
				throw new Exception("FTP-папка \"" . $ftp_dir . "/" . basename($dir) . "\" уже существует.", 146);
			}

			if (!@ftp_mkdir($this->_conn_id, $ftp_dir . "/" . basename($dir)))
			{
				$error = error_get_last();
				throw new Exception("Не удалось создать папку \"" . $ftp_dir . "/" . basename($dir) . "\". " . $error['message'], 147);
			}

			$ftp_dir = $ftp_dir . "/" . basename($dir);
		}

		/* Загрузка */
		$this->_upload_dir($dir, $ftp_dir);

		return true;
	}

	/**
	 * Скачать файл
	 * 
	 * @param string $ftp_file
	 * @param string $file
	 * @return bool
	 */
	public function download($ftp_file, $file="")
	{
		/* Проверка */
		$ftp_file = $this->_normalize_path($ftp_file);
		$this->_check_chroot($ftp_file);

		if (mb_strlen($file, "UTF-8") > 0)
		{
			$file = trim($file);
			if (mb_substr($file, 0, 1, "UTF-8") != "/")
			{
				throw new Exception("Имя файла \"" . func_get_arg(1) . "\" задано неверно.", 151);
			}
			$file = $this->_normalize_path($file);
			$fp = @fopen($file, "wb");
			if ($fp === false)
			{
				throw new Exception("Имя файла \"" . func_get_arg(1) . "\" задано неверно.", 152);
			}
			fclose($fp);
		}

		if (!$this->is_file($ftp_file))
		{
			throw new Exception("FTP-файл \"" . func_get_arg(0) . "\" не существует.", 153);
		}

		/* Скачать */
		$tmp_file = tmpfile();
		if (!@ftp_fget($this->_conn_id, $tmp_file, $ftp_file, FTP_BINARY))
		{
			$error = error_get_last();
			throw new Exception("Не удалось получить содержимое FTP-файла \"" . func_get_arg(0) . "\". " . $error['message'], 154);
		}
		fseek($tmp_file, 0);

		/* Выгрузить */
		if (mb_strlen($file, "UTF-8") < 1)
		{
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"" . basename($ftp_file) . "\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . $this->_size_file($ftp_file));

			while (!feof($tmp_file))
			{
				echo fread($tmp_file, 4096);
			}

			fclose($tmp_file);
		}
		/* Записать в файл */
		else
		{
			$fp = fopen($file, "wb");
			while (!feof($tmp_file))
			{
				fwrite($fp, fread($tmp_file, 4096));
			}

			fclose($fp);
			fclose($tmp_file);
		}

		return true;
	}

	/**
	 * Скачать папку
	 * 
	 * @param string $ftp_dir
	 * @param string $dir
	 * @return bool
	 */
	public function download_dir($ftp_dir, $dir)
	{
		/* Проверка */
		$ftp_dir = $this->_normalize_path($ftp_dir);
		$this->_check_chroot($ftp_dir);
		if (!$this->is_dir($ftp_dir))
		{
			throw new Exception("FTP-папки \"" . func_get_arg(0) . "\" не существует.", 161);
		}

		$dir = trim($dir);
		if (mb_substr($dir, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Наименование папки \"" . func_get_arg(1) . "\" задано неверно.", 162);
		}
		$dir = $this->_normalize_path($dir);
		if (!is_dir($dir))
		{
			$dir_ar = explode("/", $dir);
			$dir_name = array_pop($dir_ar);
			if (count($dir_ar) != 1)
			{
				$dir_up = implode("/", $dir_ar);
			}
			else
			{
				$dir_up = "/";
			}

			if (!is_dir($dir_up))
			{
				throw new Exception("Папка \"" . func_get_arg(1) . "\" задана неверно.", 163);
			}
			else
			{
				if (!@mkdir($dir))
				{
					$error = error_get_last();
					throw new Exception("Не удалось создать папку \"" . func_get_arg(1) . "\". " . $error['message'], 164);
				}
			}
		}
		else
		{
			$dir = $dir . "/" . basename($ftp_dir);
			if (!@mkdir($dir))
			{
				$error = error_get_last();
				throw new Exception("Не удалось создать папку \"" . func_get_arg(1) . "\". " . $error['message'], 165);
			}
		}

		/* Скачать */
		$this->_download_dir($ftp_dir, $dir);

		return true;
	}

	/**
	 * Скачать файлы и папки одним архивом
	 * 
	 * @param array|string $ftp_paths
	 * @param string $file_name
	 * @param string $zip_file
	 * @return bool
	 */
	public function zip($ftp_paths, $file_name="", $zip_file="")
	{
		/* Проверка */
		if (empty($ftp_paths))
		{
			throw new Exception("Не задана FTP-папка.", 171);
		}

		if (!is_array($ftp_paths) and !is_scalar($ftp_paths))
		{
			throw new Exception("FTP-папка, задана неверно", 172);
		}

		if (is_scalar($ftp_paths))
		{
			$ftp_paths = (array)$ftp_paths;
		}

		/* Сформировать пути */
		$this->connect();
		$ftp_basename = array();
		$ftp_path_all = array();
		foreach ($ftp_paths as $key => $val)
		{
			$path_old = $val;
			$path = $this->_normalize_path($val);
			$this->_check_chroot($path);
			$type = $this->_get_type_file($path);
			if ($type == "null")
			{
				throw new Exception("FTP-папки \"{$path_old}\" не существует.", 173);
			}

			$ftp_path_all[$key]['name'] = $this->_add_unique_name(basename($path), $ftp_basename);
			$ftp_path_all[$key]['type'] = $type;
			$ftp_path_all[$key]['path'] = $path;
		}

		/* filename */
		if (mb_strlen($file_name, "UTF-8") < 1)
		{
			$file_name = "default.zip";
		}

		$file_name = trim($file_name);
		if ($file_name == "." or $file_name == "/")
		{
			throw new Exception("Имя файла \"" . func_get_arg(1) . "\" задано неверно.", 174);
		}
		$file_name = $this->_normalize_path($file_name);
		$file_name = basename($file_name);

		/* zip_file */
		if (mb_strlen($zip_file, "UTF-8") > 0)
		{
			$zip_file = trim($zip_file);
			if (mb_substr($zip_file, 0, 1, "UTF-8") != "/")
			{
				throw new Exception("Наименование zip-файла \"" . func_get_arg(2) . "\" задано неверно.", 175);
			}
			$zip_file = $this->_normalize_path($zip_file);
			$zfp = @fopen($zip_file, "wb");
			if ($zfp === false)
			{
				throw new Exception("Имя zip-файла \"" . func_get_arg(2) . "\" задано неверно.", 176);
			}
			fclose($zfp);
			unlink($zip_file);
		}
		else
		{
			$zip_file = tempnam(sys_get_temp_dir(), "znf");
		}

		/* Создать zip-файл */
		$zip = new ZipArchive();
		$result = $zip->open($zip_file, ZIPARCHIVE::CREATE);
		if ($result !== true)
		{
			throw new Exception("Не удалось создать zip-архив в файле \"" . func_get_arg(2) . "\".", 177);
		}

		/* Заархивировать */
		foreach ($ftp_path_all as $val)
		{
			if ($val['type'] == "dir")
			{
				$this->_zip_dir($zip, $val['name'], $val['path']);
			}
			elseif ($val['type'] == "file")
			{
				$this->_zip_file($zip, $val['name'], $val['path']);
			}
		}

		$zip->close();

		/* Удалить временные файлы */
		if (!empty($this->_zip_tmp_file))
		{
			foreach ($this->_zip_tmp_file as $val)
			{
				@unlink($val);
			}
		}

		/* Выдать */
		$func_args = func_get_args();
		if (mb_strlen($func_args[2], "UTF-8") < 1)
		{
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"{$file_name}\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . filesize($zip_file));

			$zp = fopen($zip_file, "rb");
			while (!feof($zp))
			{
				echo fread($zp, 4096);
			}
			fclose($zp);

			unlink($zip_file);
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
		if (mb_strlen($path, "UTF-8") < 1)
		{
			throw new Exception("Путь задан неверно. Пустая строка.", 181);
		}

		/* Символ "." */
		if ($path == "." or $path == "/")
		{
			return true;
		}

		/* Строка с нулевым символом */
		$strlen_before = mb_strlen($path, "UTF-8");
		$path = str_replace(chr(0), '', $path);
		$strlen_after = mb_strlen($path, "UTF-8");
		if ($strlen_before != $strlen_after)
		{
			throw new Exception("Путь задан неверно. Нулевой символ.", 182);
		}

		/* Бинарная строка, либо символы не в UTF-8 */
		$result = mb_detect_encoding($path, "UTF-8");
		if ($result === false)
		{
			throw new Exception("Путь задан неверно. Бинарная строка, либо символы не в UTF-8.", 183);
		}

		/* Очень большая строка */
		if (mb_strlen($path, "UTF-8") > 1024)
		{
			throw new Exception("Путь задан неверно. Очень большая строка.", 184);
		}

		/* Недопустимые символы */
		$result = strpbrk($path, "\n\r\t\v\f\$\\");
		if ($result !== false)
		{
			throw new Exception("Путь задан неверно. Недопустимые символы.", 185);
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
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Использовать имя файла как \"..\" и \".\" запрещено.", 186);
			}

			/* Строка с начальными или конечными пробелами */
			$strlen = mb_strlen($val, "UTF-8");
			$strlen_trim = mb_strlen(trim($val), "UTF-8");
			if ($strlen != $strlen_trim)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Пробелы в начале или в конце имени файла.", 187);
			}

			/* Не указано имя файла */
			$val_trim = trim($val);
			if (mb_strlen($val_trim, "UTF-8") < 1)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Не задано имя файла.", 188);
			}
		}

		return true;
	}

	/**
	 * Привести путь к нормальному виду
	 * 
	 * @param string $path
	 * @return string
	 */
	private function _normalize_path($path)
	{
		/* Проверка */
		$this->_check_path($path);
		$path = (string) $path;
		$path = trim($path);

		/* Символ "." */
		if ($path == ".")
		{
			return $this->_path;
		}

		/* Корень */
		if ($path == "/")
		{
			return $path;
		}

		/* Нормализация */
		if (mb_substr($path, 0, 1, "UTF-8") != "/")
		{
			$path = $this->_path . "/" . $path;
		}

		if (mb_substr($path, mb_strlen($path, "UTF-8") - 1, 1, "UTF-8") == "/")
		{
			$path = mb_substr($path, 0, mb_strlen($path, "UTF-8") - 1);
		}

		return $path;
	}

	/**
	 * Получить тип файла (null|file|dir)
	 * 
	 * @param string $file
	 * @return string
	 */
	private function _get_type_file($file)
	{
		/* Нормализация */
		if ($file == "/")
		{
			return "dir";
		}

		/* Тип файла */
		$type = "null";

		/* На уровень повыше */
		$file_ar = explode("/", $file);
		$file_name = array_pop($file_ar);
		if (count($file_ar) != 1)
		{
			$file_up = implode("/", $file_ar);
		}
		else
		{
			$file_up = "/";
		}

		/* FTP raw */
		$raw_list_up = ftp_rawlist($this->_conn_id, $file_up);
		if (empty($raw_list_up))
		{
			return $type;
		}
		foreach ($raw_list_up as $val)
		{
			$file_settings = $this->_raw_razbor($val);
			if ($file_settings['name'] == $file_name)
			{
				$type = $file_settings['type'];
			}
		}

		return $type;
	}

	/**
	 * Разбор строки полученной функцией ftp_rawlist
	 * 
	 * @param string $str
	 * @return array 
	 */
	private function _raw_razbor($str)
	{
		if (!preg_match("#([-d][rwxstST-]+).* ([0-9]*) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)#isu", $str, $sovpal))
		{
			return false;
		}

		$file_settings = array();
		if (mb_substr($sovpal[1], 0, 1, "UTF-8") == "d")
		{
			$file_settings['type'] = "dir";
		}
		else
		{
			$file_settings['type'] = "file";
		}

		$file_settings['line'] = $sovpal[0];
		$file_settings['rights'] = $sovpal[1];
		$file_settings['number'] = $sovpal[2];
		$file_settings['user'] = $sovpal[3];
		$file_settings['group'] = $sovpal[4];
		$file_settings['size'] = $sovpal[5];
		$file_settings['date'] = date("d.m.Y", strtotime($sovpal[6]));
		$file_settings['time'] = $sovpal[7];
		$file_settings['name'] = $sovpal[9];

		return $file_settings;
	}

	/**
	 * Копировать файл
	 *
	 * @param string $source
	 * @param string $dest
	 * @return bool
	 */
	private function _cp_file($source, $dest)
	{
		$tmp_file = tmpfile();
		if (!@ftp_fget($this->_conn_id, $tmp_file, $source, FTP_BINARY))
		{
			$error = error_get_last();
			throw new Exception("Не удалось получить содержимое FTP-файла \"{$source}\". " . $error['message'], 191);
		}
		fseek($tmp_file, 0);
		if (!@ftp_fput($this->_conn_id, $dest, $tmp_file, FTP_BINARY))
		{
			$error = error_get_last();
			throw new Exception("Не удалось загрузить строку в FTP-файл \"{$dest}\". " . $error['message'], 192);
		}
		fclose($tmp_file);

		return true;
	}

	/**
	 * Копировать папку
	 *
	 * @param string $source
	 * @param string $dest
	 * @return bool
	 */
	private function _cp_dir($source, $dest)
	{
		if (!$this->is_dir($dest))
		{
			$this->mkdir($dest);
		}

		$files = $this->ls($source);
		if (!empty($files))
		{
			foreach ($files as $val)
			{
				/* Копировать файл */
				if ($val['type'] == "file")
				{
					$this->_cp_file($source . "/" . $val['name'], $dest . "/" . $val['name']);
				}
				/* Копировать папку */
				elseif ($val['type'] == "dir")
				{
					$this->_cp_dir($source . "/" . $val['name'], $dest . "/" . $val['name']);
				}
			}
		}

		return true;
	}

	/**
	 * Удалить папку
	 *
	 * @param string $dir
	 * @return bool
	 */
	private function _rm_dir($dir)
	{
		/* Список файлов */
		$files = $this->ls($dir);

		if (!empty($files))
		{
			foreach ($files as $val)
			{
				/* Удалить файл */
				if ($val['type'] == "file")
				{
					if (!@ftp_delete($this->_conn_id, $dir . "/" . $val['name']))
					{
						$error = error_get_last();
						throw new Exception("Не удалось удалить FTP-файл \"." . $dir . "/" . $val['name'] . "\". " . $error['message'], 201);
					}
				}
				/* Удалить папку */
				elseif ($val['type'] == "dir")
				{
					$this->_rm_dir($dir . "/" . $val['name']);
				}
			}
		}

		/* Удалить пустую папку */
		if (!@ftp_rmdir($this->_conn_id, $dir))
		{
			$error = error_get_last();
			throw new Exception("Не удалось удалить FTP-папку \"{$dir}\". " . $error['message'], 202);
		}

		return true;
	}

	/**
	 * Рекурсивно установить права на папку
	 * 
	 * @param type $dir
	 * @param type $mode
	 * @return bool
	 */
	private function _chmod_dir($dir, $mode)
	{
		/* Текущая папка */
		if (!@ftp_chmod($this->_conn_id, $mode, $dir))
		{
			$error = error_get_last();
			throw new Exception("Не удалось установить права \"{$mode}\" на FTP-папку \"{$dir}\". " . $error['message'], 211);
		}

		$files = $this->ls($dir);
		if (!empty($files))
		{
			foreach ($files as $val)
			{
				/* Файл */
				if ($val['type'] == "file")
				{
					if (!@ftp_chmod($this->_conn_id, $mode, $dir . "/" . $val['name']))
					{
						$error = error_get_last();
						throw new Exception("Не удалось установить права \"{$mode}\" на FTP-файл \"" . $dir . "/" . $val['name'] . "\". " . $error['message'], 212);
					}
				}
				/* Папка */
				elseif ($val['type'] == "dir")
				{
					$this->_chmod_dir($dir . "/" . $val['name'], $mode);
				}
			}
		}

		return true;
	}

	/**
	 * Получить размер файла
	 * 
	 * @param string $file
	 * @return int 
	 */
	private function _size_file($file)
	{
		$raw_list = ftp_rawlist($this->_conn_id, $file);
		$file_raw = array_pop($raw_list);
		$file_settings = $this->_raw_razbor($file_raw);
		$size = $file_settings['size'];

		return $size;
	}

	/**
	 * Получить размер папки в байтах
	 * 
	 * @param string $dir
	 * @return int
	 */
	private function _size_dir($dir)
	{
		$size = 0;

		$files = $this->ls($dir);
		if (!empty($files))
		{
			foreach ($files as $val)
			{
				if ($val['type'] == "file")
				{
					$size += $val['size'];
				}
				elseif ($val['type'] == "dir")
				{
					$size += $val['size'];
					$size += $this->_size_dir($dir . "/" . $val['name']);
				}
			}
		}

		return $size;
	}

	/**
	 * Закачать папку
	 * 
	 * @param string $dir
	 * @param string $ftp_dir
	 * @return bool
	 */
	private function _upload_dir($dir, $ftp_dir)
	{
		$ls = scandir($dir);
		if (!empty($ls))
		{
			foreach ($ls as $val)
			{
				/* Точки */
				if ($val == "." or $val == "..")
				{
					continue;
				}

				/* Папка */
				if (is_dir($dir . "/" . $val))
				{
					if (!@ftp_mkdir($this->_conn_id, $ftp_dir . "/" . $val))
					{
						$error = error_get_last();
						throw new Exception("Не удалось создать FTP-папку \"" . $ftp_dir . "/" . $val . "\". " . $error['message'], 221);
					}

					$this->_upload_dir($dir . "/" . $val, $ftp_dir . "/" . $val);
				}

				/* Файл */
				if (is_file($dir . "/" . $val))
				{
					$fp = fopen($dir . "/" . $val, "rb");
					if (!@ftp_fput($this->_conn_id, $ftp_dir . "/" . $val, $fp, FTP_BINARY))
					{
						$error = error_get_last();
						throw new Exception("Не удалось записать в FTP-файл \"" . $ftp_dir . "/" . $val . "\". " . $error['message'], 222);
					}
					fclose($fp);
				}
			}
		}

		return true;
	}

	/**
	 * Скачать папку
	 * 
	 * @param string $ftp_dir
	 * @param string $dir
	 * @return bool
	 */
	private function _download_dir($ftp_dir, $dir)
	{
		$ls = array();
		$raw_list = ftp_rawlist($this->_conn_id, $ftp_dir);
		if (!empty($raw_list))
		{
			foreach ($raw_list as $val)
			{
				$file_settings = $this->_raw_razbor($val);
				if (empty($file_settings) or $file_settings['name'] == "." or $file_settings['name'] == "..")
				{
					continue;
				}

				if ($file_settings['type'] == "dir")
				{
					if (!@mkdir($dir . "/" . $file_settings['name']))
					{
						$error = error_get_last();
						throw new Exception("Не удалось создать папку \"" . $dir . "/" . $file_settings['name'] . "\". " . $error['message'], 231);
					}

					$this->_download_dir($ftp_dir . "/" . $file_settings['name'], $dir . "/" . $file_settings['name']);
				}
				elseif ($file_settings['type'] == "file")
				{
					$ftp_file = $ftp_dir . "/" . $file_settings['name'];
					$fp = fopen($dir . "/" . $file_settings['name'], "wb");
					if (!@ftp_fget($this->_conn_id, $fp, $ftp_file, FTP_BINARY))
					{
						$error = error_get_last();
						throw new Exception("Не удалось получить содержимое FTP-файла \"{$ftp_file}\". " . $error['message'], 232);
					}
					fclose($fp);
				}
			}
		}

		return true;
	}

	/**
	 * Добавить уникальное имя
	 * 
	 * @param string $name
	 * @param array $paths 
	 * @return string
	 */
	private function _add_unique_name($name, &$paths)
	{
		if (in_array($name, $paths))
		{
			$name = "_" . $name;
			$name = $this->_add_unique_name($name, $paths);
		}

		$paths[] = $name;
		return $name;
	}

	/**
	 * Скачать FTP папку и поместить в zip-архив
	 * 
	 * @param ZipArchive $zip
	 * @param string $name
	 * @param type $ftp_dir
	 * @return bool
	 */
	private function _zip_dir(&$zip, $name, $ftp_dir)
	{
		$zip->addEmptyDir($name);

		$raw_list = ftp_rawlist($this->_conn_id, $ftp_dir);
		if (!empty($raw_list))
		{
			foreach ($raw_list as $val)
			{
				$file_settings = $this->_raw_razbor($val);
				if (empty($file_settings) or $file_settings['name'] == "." or $file_settings['name'] == "..")
				{
					continue;
				}

				if ($file_settings['type'] == "dir")
				{
					$this->_zip_dir($zip, $name . "/" . $file_settings['name'], $ftp_dir . "/" . $file_settings['name']);
				}
				elseif ($file_settings['type'] == "file")
				{
					$this->_zip_file($zip, $name . "/" . $file_settings['name'], $ftp_dir . "/" . $file_settings['name']);
				}
			}
		}

		return true;
	}

	/**
	 * Скачать файл и поместить в zip архив
	 * 
	 * @param ZipArchive $zip
	 * @param string $name
	 * @param string $ftp_file
	 * @return bool
	 */
	private function _zip_file(&$zip, $name, $ftp_file)
	{
		$tmpfile = tempnam(sys_get_temp_dir(), "znf");
		$fp = fopen($tmpfile, "wb");
		if (!@ftp_fget($this->_conn_id, $fp, $ftp_file, FTP_BINARY))
		{
			$error = error_get_last();
			throw new Exception("Не удалось получить содержимое FTP-файла \"{$ftp_file}\". " . $error['message'], 241);
		}
		fclose($fp);

		$zip->addFile($tmpfile, $name);
		$this->_zip_tmp_file[] = $tmpfile;

		return true;
	}

	/**
	 * Проверить путь на chroot
	 * 
	 * @param string $path
	 * @return bool
	 */
	private function _check_chroot($path)
	{
		if ($this->_chroot)
		{
			if (mb_substr($path, 0, mb_strlen($this->_path, "UTF-8"), "UTF-8") != $this->_path)
			{
				throw new Exception("Путь \"{$path}\" вышел за пределы chroot.", 251);
			}

			if (mb_strlen($path, "UTF-8") > mb_strlen($this->_path, "UTF-8"))
			{
				if (mb_substr($path, mb_strlen($this->_path, "UTF-8"), 1, "UTF-8") != "/")
				{
					throw new Exception("Путь \"{$path}\" вышел за пределы chroot.", 252);
				}
			}
		}

		return true;
	}

}

?>
