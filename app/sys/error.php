<?php
class Err
{
	/**
	 * Все ошибки
	 * 
	 * @var array
	 */
	private static $error = array();
	
	/**
	 * Добавить
	 * 
	 * @param string $error
	 * @param string $name
	 * @return bool
	 */
	public static function add($error, $name)
	{
		self::$error[$name] = $error;
		
		return true;
	}
	
	/**
	 * Получить список ошибок
	 * 
	 * @return bool
	 */
	public static function get()
	{
		return self::$error;
	}
	
	/**
	 * Исключение для ошибок
	 * 
	 * @return bool
	 */
	public static function exception($error="")
	{
		if(count(self::$error)!=0)
		{throw new Exception_User($error);}
		
		return true;
	}
}
?>
