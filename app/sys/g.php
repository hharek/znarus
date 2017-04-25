<?php
/**
 * Глобавльные переменные
 */
class G
{
	/**
	 * Массив параметров
	 * 
	 * @var array
	 */
	private static $_var = [];
	
	/**
	 * Функции
	 * 
	 * @param string $func
	 * @param array $args
	 * @return mixed
	 */
	public static function __callStatic($func, $args)
	{
		/* Прочитать */
		if (count($args) === 0)
		{
			if (array_key_exists($func, self::$_var) === false)
			{
				throw new Exception("Глобальной переменной «{$func}» не существует.");
			}
			
			return self::$_var[$func];
		}
		/* Создать */
		elseif (count($args) === 1)
		{
			self::$_var[$func] = $args[0];
		}
		/* Много аргументов */
		else
		{
			throw new Exception("Укажите одно значение для глобальной переменной «{$func}».");
		}
	}
	
	/**
	 * Удалить переменую
	 * 
	 * @param string $name
	 */
	public static function _unset($name)
	{
		if (array_key_exists($name, self::$_var) === false)
		{
			throw new Exception("Глобальной переменной «{$name}» не существует.");
		}

		unset(self::$_var[$name]);
	}
	
	/**
	 * Проверить переменую на существование
	 * 
	 * @param string $name
	 */
	public static function _isset($name)
	{
		return array_key_exists($name, self::$_var);
	}
	
	/**
	 * Получить значение глобальной переменной
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public static function _get($name)
	{
		if (array_key_exists($name, self::$_var) === false)
		{
			throw new Exception("Глобальной переменной «{$name}» не существует.");
		}

		return self::$_var[$name];
	}
	
	/**
	 * Назначить глобальную переменную
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public static function _set($name, $value)
	{
		$name = (string)$name;
		if ($name === "")
		{
			throw new Exception("Укажите имя для глобальной переменной.");
		}
		
		if (ctype_alnum(str_replace("_", "", $name)) === false)
		{
			throw new Exception("Имя глобальной переменной задано неверно. Допускаются символы: a-z,0-9,\"_\" .");
		}
		
		self::$_var[$name] = $value;
	}
	
	/**
	 * Вернуть все глобальные переменные
	 * 
	 * @return array
	 */
	public static function _all($key_only = false)
	{
		if ($key_only === false)
		{
			return self::$_var;
		}
		elseif ($key_only === true)
		{
			return array_keys(self::$_var);
		}
	}

	/**
	 * @return _Pgsql
	 */
	private static function db(){}
	
	/**
	 * @return _Pgsql
	 */
	private static function db_core(){}
	
	/**
	 * @return _File
	 */
	private static function file(){}
	
	/**
	 * @return _File
	 */
	private static function file_app(){}
	
	/**
	 * @return _Cache
	 */
	private static function cache_db(){}
	
	/**
	 * @return _Cache
	 */
	private static function cache_db_core(){}
	
	/**
	 * @return _Cache
	 */
	private static function cache_route(){}
	
	/**
	 * @return _Cache
	 */
	private static function cache_page(){}
	
	/**
	 * @return _Cache
	 */
	private static function cache_ajax(){}
	
	/**
	 * @return _Version
	 */
	private static function version(){}
	
	/**
	 * @return _Draft
	 */
	private static function draft(){}
}
?>