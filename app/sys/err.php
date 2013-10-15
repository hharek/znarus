<?php
/**
 * Класс для работы с ошибками при заполнении формы
 */
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
		{throw new Exception_Form($error);}
		
		return true;
	}
	
	/**
	 * Проверить поле
	 * 
	 * @param string $value
	 * @param string $type
	 * @param bool $empty_allow
	 * @param string $identified
	 * @param string $name
	 * @return boolean
	 */
	public static function check_field($value, $type, $empty_allow, $identified, $name)
	{
		$value = (string)$value;
		if(mb_strlen($value) == 0 )
		{
			if(!$empty_allow)
			{
				self::add("Поле «{$name}» не заполнено.", $identified);
			}
		}
		else
		{
			if(!Chf::$type($value))
			{
				self::add("Поле «{$name}» задано неверно. ".Chf::error(), $identified);
			}
		}
		
		return true;
	}
}
?>
