<?php

/**
 * Класс для работы с файлами
 * 
 * @category	File System
 * @author		Sergeev Denis <hharek@yandex.ru>
 * @copyright	2011 Sergeev Denis
 * @license		https://github.com/hharek/zn_file/wiki/MIT-License MIT License
 * @version		0.1.2
 * @link		https://github.com/hharek/zn_file/
 */
class ZN_File
{

	/**
	 * Основная папка
	 * 
	 * @var string
	 */
	private $_path = "/";

	/**
	 * Состояние chroot
	 * 
	 * @var bool
	 */
	private $_chroot = false;

	/**
	 * Конструктор
	 * 
	 * @param string $path
	 * @return bool
	 */
	public function __construct($path = "/")
	{
		$path = trim($path);
		if (mb_strlen($path, "UTF-8") < 1)
		{
			throw new Exception("Корневая папка не задана.", 11);
		}
		if (mb_substr($path, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Корневая папка \"" . func_get_arg(0) . "\" задана неверно.", 12);
		}
		$path = $this->_normalize_path($path);

		if (!is_dir($path))
		{
			throw new Exception("Папки \"" . func_get_arg(0) . "\" не существует.", 13);
		}

		$this->_path = $path;

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
		if ($path == $this->_path)
		{
			return true;
		}

		$path = trim($path);
		if (mb_substr($path, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Наименование папки \"" . func_get_arg(0) . "\" задано неверно.", 21);
		}
		$path = $this->_normalize_path($path);
		if (!is_dir($path))
		{
			throw new Exception("Папки \"" . func_get_arg(0) . "\" не существует.", 22);
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
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		if (is_file($file))
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
		$path = $this->_normalize_path($path);
		$this->_check_path($path);

		if (is_dir($path))
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

		if (!in_array($type, array('all', 'file', 'dir')))
		{
			throw new Exception("Тип \"" . func_get_arg(1) . "\" задан неверно. Необходимо указать: (all|file|dir).", 31);
		}

		if ($type != "file" and mb_strlen($ext, "UTF-8") > 0)
		{
			throw new Exception("Расширение можно задать только для файлов", 32);
		}

		if (mb_strlen($ext, "UTF-8") > 0 and !preg_match("#^[a-zA-Z0-9]{1,5}$#isu", $ext))
		{
			throw new Exception("Расширение \"" . func_get_arg(2) . "\" задано неверно", 33);
		}

		/* Список */
		if (!is_dir($path))
		{
			throw new Exception("Папки \"" . func_get_arg(0) . "\" не существует", 34);
		}

		$ls = array();
		$scandir = scandir($path);
		if (!empty($scandir))
		{
			foreach ($scandir as $val)
			{
				if ($val == ".." or $val == ".")
				{
					continue;
				}

				/* Тип файла */
				if (is_file($path . "/" . $val))
				{
					$file_type = "file";
				}
				elseif (is_dir($path . "/" . $val))
				{
					$file_type = "dir";
				}
				else
				{
					continue;
				}

				switch ($type)
				{
					case "all":
						{
							$ls[] = array("name" => $val, "type" => $file_type);
						}
						break;

					case "dir":
						{
							if ($file_type == "dir")
							{
								$ls[] = array("name" => $val, "type" => "dir");
							}
						}
						break;

					case "file":
						{
							if ($file_type == "file")
							{
								/* Поиск по расширению */
								if (mb_substr($val, mb_strlen($val, "UTF-8") - mb_strlen($ext, "UTF-8"), mb_strlen($ext, "UTF-8"), "UTF-8") == $ext)
								{
									$ls[] = array("name" => $val, "type" => "file");
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
		$file = $this->_normalize_path($file);
		$this->_check_path($file);

		if (!is_file($file))
		{
			throw new Exception("Файла \"" . func_get_arg(0) . "\" не существует", 41);
		}

		$content = @file_get_contents($file);
		if ($content === false)
		{
			$error = error_get_last();
			throw new Exception("Не удалось прочитать файл \"" . func_get_arg(0) . "\". " . $error['message'], 42);
		}

		return $content;
	}

	/**
	 * Записать данные в файл
	 * 
	 * @param string $file
	 * @param string $content 
	 * @return bool
	 */
	public function put($file, $content)
	{
		$file = $this->_normalize_path($file);
		$this->_check_chroot($file);

		$result = @file_put_contents($file, $content);
		if ($result === false)
		{
			$error = error_get_last();
			throw new Exception("Не удалось записать данные в файл \"" . func_get_arg(0) . "\". " . $error['message'], 51);
		}

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
		$path = $this->_normalize_path($path);
		$this->_check_chroot($path);

		$result = @mkdir($path, 0755);
		if ($result === false)
		{
			$error = error_get_last();
			throw new Exception("Не удалось создать папку \"" . func_get_arg(0) . "\". " . $error['message'], 52);
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

		$dest = $this->_normalize_path($dest);
		$this->_check_chroot($dest);

		/* cp /home/dir1 /home/dir1/dir2 */
		if (mb_substr($dest, 0, mb_strlen($source, "UTF-8"), "UTF-8") == $source)
		{
			if (mb_substr($dest, mb_strlen($source, "UTF-8"), 1, "UTF-8") == "/")
			{
				throw new Exception("Файл источник \"" . func_get_arg(0) . "\" не должен входить в файл назначения \"" . func_get_arg(1) . "\" ", 61);
			}
		}
		
		/* Копирование */
		if (is_file($source))
		{
			if (is_dir($dest))
			{
				$dest .= "/" . basename($source);
			}
			if (!@copy($source, $dest))
			{
				$error = error_get_last();
				throw new Exception("Не удалось скопировать \"" . func_get_arg(0) . "\" в \"" . func_get_arg(1) . "\". " . $error['message'], 62);
			}
		}
		elseif (is_dir($source))
		{
			$dest .= "/" . basename($source);

			if ($source == $dest)
			{
				throw new Exception("Папка источник и папка назначения - это одна и та же папка", 63);
			}

			$this->_cp_dir($source, $dest);
		}
		else
		{
			throw new Exception("Имя файла \"" . func_get_arg(0) . "\" задано неверно.", 64);
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

		$dest = $this->_normalize_path($dest);
		$this->_check_chroot($dest);

		if(is_dir($dest))
		{
			$dest .= "/".  basename($source);
		}
		
		if (!@rename($source, $dest))
		{
			$error = error_get_last();
			throw new Exception("Не удалось перенести \"" . func_get_arg(0) . "\" в \"" . func_get_arg(1) . "\". " . $error['message'], 71);
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

		/* Удаление */
		if (is_file($file))
		{
			if (!@unlink($file))
			{
				$error = error_get_last();
				throw new Exception("Не удалось удалить \"" . func_get_arg(0) . "\". " . $error['message'], 81);
			}
		}
		elseif (is_dir($file))
		{
			$this->_rm_dir($file);
		}
		else
		{
			throw new Exception("Имя файла \"" . func_get_arg(0) . "\" задано неверно", 82);
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

		$mode = (int) $mode;
		$recursion = (bool) $recursion;

		/* Установить права доступа */
		if ($recursion == false or is_file($file))
		{
			if (!@chmod($file, $mode))
			{
				$error = error_get_last();
				throw new Exception("Не удалось установить права \"" . func_get_arg(1) . "\" на файл \"" . func_get_arg(0) . "\". " . $error['message'], 91);
			}
		}
		elseif (is_dir($file))
		{
			$this->_chmod_dir($file, $mode);
		}
		else
		{
			throw new Exception("Файла \"" . func_get_arg(0) . "\" не существует", 92);
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

		/* Получить размер */
		if (is_file($file))
		{
			$size = filesize($file);
		}
		elseif (is_dir($file))
		{
			$size = $this->_size_dir($file);
		}
		else
		{
			throw new Exception("Файла \"" . func_get_arg(0) . "\" не существует", 101);
		}

		return $size;
	}

	/**
	 * Загрузить файл через форму
	 * 
	 * @param string $source
	 * @param string $dest
	 * @return bool
	 */
	public function upload($source, $dest, $check_form_upload=false)
	{
		/* Проверка источника */
		$source = trim($source);
		if (mb_substr($source, 0, 1, "UTF-8") != "/")
		{
			throw new Exception("Имя файла источника \"" . func_get_arg(0) . "\" задано неверно.", 111);
		}
		$source = $this->_normalize_path($source);
		if (!is_file($source))
		{
			throw new Exception("Файла источника \"" . func_get_arg(0) . "\" не существует.", 112);
		}

		/* Проверка файла назначения */
		$dest = $this->_normalize_path($dest);
		$this->_check_chroot($dest);

		$check_form_upload = (bool) $check_form_upload;
		if ($check_form_upload)
		{
			if (!is_uploaded_file($source))
			{
				throw new Exception("Файл источник \"" . func_get_arg(0) . "\" загружен не при помощи HTTP POST", 113);
			}
		}

		/* Загрузить */
		if (!@copy($source, $dest))
		{
			$error = error_get_last();
			throw new Exception("Не удалось загрузить файл. " . $error['message'], 114);
		}

		return true;
	}

	/**
	 * Скачать файл
	 * 
	 * @param string $source
	 * @param string $dest
	 * @return bool
	 */
	public function download($source, $dest="")
	{
		/* Проверка */
		$source = $this->_normalize_path($source);
		$this->_check_chroot($source);

		if (!is_file($source))
		{
			throw new Exception("Файла \"" . func_get_arg(0) . "\" не существует.", 121);
		}

		if (mb_strlen($dest, "UTF-8") > 0)
		{
			$dest = trim($dest);
			if (mb_substr($dest, 0, 1, "UTF-8") != "/")
			{
				throw new Exception("Имя файла \"" . func_get_arg(1) . "\" задано неверно.", 122);
			}
			$dest = $this->_normalize_path($dest);

			$fp = @fopen($dest, "wb");
			if ($fp === false)
			{
				throw new Exception("Имя файла \"" . func_get_arg(1) . "\" задано неверно.", 123);
			}
			fclose($fp);
		}

		/* Скачать */
		if (mb_strlen($dest, "UTF-8") < 1)
		{
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"" . basename($source) . "\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . filesize($source));

			$fp = @fopen($source, "rb");
			if ($fp === false)
			{
				$error = error_get_last();
				throw new Exception("Не удалось открыть файл \"" . func_get_arg(0) . "\"." . $error['message'], 124);
			}
			while (!feof($fp))
			{
				echo fread($fp, 4096);
			}
			fclose($fp);
		}

		/* Скачать в файл */
		else
		{
			$fp = @fopen($source, "rb");
			if ($fp === false)
			{
				$error = error_get_last();
				throw new Exception("Не удалось открыть файл \"" . func_get_arg(0) . "\"." . $error['message'], 125);
			}

			$fw = @fopen($dest, "wb");
			while (!feof($fp))
			{
				fwrite($fw, fread($fp, 4096), 4096);
			}
			fclose($fp);
			fclose($fw);
		}

		return true;
	}

	/**
	 * Скачать файлы и папки одним архивом
	 * 
	 * @param array|string $paths
	 * @param string $file_name
	 * @param string $zip_file
	 * @return bool
	 */
	public function zip($paths, $file_name="", $zip_file="")
	{
		/* Проверка */
		if (empty($paths))
		{
			throw new Exception("Не задана папка.", 131);
		}

		if (!is_array($paths) and !is_scalar($paths))
		{
			throw new Exception("Папка, задана неверно", 132);
		}

		if (is_scalar($paths))
		{
			$paths = (array) $paths;
		}

		/* Сформировать пути */
		$basename = array();
		$path_all = array();
		foreach ($paths as $key => $val)
		{
			$path_old = $val;
			$path = $this->_normalize_path($val);
			$this->_check_chroot($path);

			if (is_file($path))
			{
				$path_all[$key]['type'] = "file";
			}
			elseif (is_dir($path))
			{
				$path_all[$key]['type'] = "dir";
			}
			else
			{
				throw new Exception("Папки \"{$path_old}\" не существует.", 133);
			}

			$path_all[$key]['name'] = $this->_add_unique_name(basename($path), $basename);
			$path_all[$key]['path'] = $path;
		}

		/* filename */
		if (mb_strlen($file_name, "UTF-8") < 1)
		{
			$file_name = "default.zip";
		}

		$file_name = trim($file_name);
		if ($file_name == "." or $file_name == "/")
		{
			throw new Exception("Имя файла \"" . func_get_arg(1) . "\" задано неверно.", 134);
		}
		$file_name = $this->_normalize_path($file_name);
		$file_name = basename($file_name);

		/* zip_file */
		if (mb_strlen($zip_file, "UTF-8") > 0)
		{
			$zip_file = trim($zip_file);
			if (mb_substr($zip_file, 0, 1, "UTF-8") != "/")
			{
				throw new Exception("Наименование zip-файла \"" . func_get_arg(2) . "\" задано неверно.", 135);
			}
			$zip_file = $this->_normalize_path($zip_file);
			$zfp = @fopen($zip_file, "wb");
			if ($zfp === false)
			{
				throw new Exception("Имя zip-файла \"" . func_get_arg(2) . "\" задано неверно.", 136);
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
			throw new Exception("Не удалось создать zip-архив в файле \"" . func_get_arg(2) . "\".", 137);
		}

		/* Заархивировать */
		foreach ($path_all as $val)
		{
			if ($val['type'] == "dir")
			{
				$this->_zip_dir($zip, $val['name'], $val['path']);
			}
			elseif ($val['type'] == "file")
			{
				$zip->addFile($val['path'], $val['name']);
			}
		}

		$zip->close();

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
			throw new Exception("Путь задан неверно. Пустая строка.", 141);
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
			throw new Exception("Путь задан неверно. Нулевой символ.", 142);
		}

		/* Бинарная строка, либо символы не в UTF-8 */
		$result = mb_detect_encoding($path, "UTF-8");
		if ($result === false)
		{
			throw new Exception("Путь задан неверно. Бинарная строка, либо символы не в UTF-8.", 143);
		}

		/* Очень большая строка */
		if (mb_strlen($path, "UTF-8") > 1024)
		{
			throw new Exception("Путь задан неверно. Очень большая строка.", 144);
		}

		/* Недопустимые символы */
		$result = strpbrk($path, "\n\r\t\v\f\$\\");
		if ($result !== false)
		{
			throw new Exception("Путь задан неверно. Недопустимые символы.", 145);
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
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Использовать имя файла как \"..\" и \".\" запрещено.", 146);
			}

			/* Строка с начальными или конечными пробелами */
			$strlen = mb_strlen($val, "UTF-8");
			$strlen_trim = mb_strlen(trim($val), "UTF-8");
			if ($strlen != $strlen_trim)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Пробелы в начале или в конце имени файла.", 147);
			}

			/* Не указано имя файла */
			$val_trim = trim($val);
			if (mb_strlen($val_trim, "UTF-8") < 1)
			{
				throw new Exception("Путь \"" . func_get_arg(0) . "\" задан неверно. Не задано имя файла.", 148);
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
					if (!@copy($source . "/" . $val['name'], $dest . "/" . $val['name']))
					{
						$error = error_get_last();
						throw new Exception("Не удалось скопировать \"" . $source . "/" . $val['name'] . "\" в \"" . $dest . "/" . $val['name'] . "\". " . $error['message'], 151);
					}
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
					if (!@unlink($dir . "/" . $val['name']))
					{
						$error = error_get_last();
						throw new Exception("Не удалось удалить файл \"." . $dir . "/" . $val['name'] . "\". " . $error['message'], 161);
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
		if (!@rmdir($dir))
		{
			$error = error_get_last();
			throw new Exception("Не удалось удалить папку \"{$dir}\". " . $error['message'], 162);
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
		if (!@chmod($dir, $mode))
		{
			$error = error_get_last();
			throw new Exception("Не удалось установить права \"{$mode}\" на папку \"{$dir}\". " . $error['message'], 171);
		}

		$files = $this->ls($dir);
		if (!empty($files))
		{
			foreach ($files as $val)
			{
				/* Файл */
				if ($val['type'] == "file")
				{
					if (!@chmod($dir . "/" . $val['name'], $mode))
					{
						$error = error_get_last();
						throw new Exception("Не удалось установить права \"{$mode}\" на файл \"" . $dir . "/" . $val['name'] . "\". " . $error['message'], 172);
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
	 * Получить размер папки в байтах
	 * 
	 * @param string $dir
	 * @return int
	 */
	private function _size_dir($dir)
	{
		$size = filesize($dir);

		$files = $this->ls($dir);
		if (!empty($files))
		{
			foreach ($files as $val)
			{
				if ($val['type'] == "file")
				{
					$size += filesize($dir . "/" . $val['name']);
				}
				elseif ($val['type'] == "dir")
				{
					$size += $this->_size_dir($dir . "/" . $val['name']);
				}
			}
		}

		return $size;
	}

	/**
	 * Проверить путь на chroot
	 * 
	 * @param string $path
	 * @return bool
	 */
	public function _check_chroot($path)
	{
		if ($this->_chroot)
		{
			if (mb_substr($path, 0, mb_strlen($this->_path, "UTF-8"), "UTF-8") != $this->_path)
			{
				throw new Exception("Путь \"{$path}\" вышел за пределы chroot.", 181);
			}

			if (mb_strlen($path, "UTF-8") > mb_strlen($this->_path, "UTF-8"))
			{
				if (mb_substr($path, mb_strlen($this->_path, "UTF-8"), 1, "UTF-8") != "/")
				{
					throw new Exception("Путь \"{$path}\" вышел за пределы chroot.", 182);
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
	 * @param type $dir
	 * @return bool
	 */
	private function _zip_dir(&$zip, $name, $dir)
	{
		$zip->addEmptyDir($name);

		$ls = $this->ls($dir);
		if (!empty($ls))
		{
			foreach ($ls as $val)
			{
				if ($val['type'] == "dir")
				{
					$this->_zip_dir($zip, $name . "/" . $val['name'], $dir . "/" . $val['name']);
				}
				elseif ($val['type'] == "file")
				{
					$zip->addFile($dir . "/" . $val['name'], $name . "/" . $val['name']);
				}
			}
		}

		return true;
	}

}

?>
