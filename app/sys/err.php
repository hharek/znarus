<?php
/* Исключения срабатываемые при ошибке в форме */
class Exception_Form extends Exception {}

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
	private static $error = [];

	/**
	 * Добавить
	 * 
	 * @param string $error
	 * @param string $name
	 */
	public static function add($error, $name)
	{
		if (!isset(self::$error[$name]))
		{
			self::$error[$name] = $error;
		}
	}

	/**
	 * Получить список ошибок
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function get($name = null)
	{
		if ($name === null)
		{
			return self::$error;
		}
		elseif ($name !== null)
		{
			if (isset(self::$error[$name]))
			{
				return self::$error[$name];
			}
			else
			{
				return;
			}
		}
	}

	/**
	 * Исключение для ошибок
	 */
	public static function exception()
	{
		if (count(self::$error) !== 0)
		{
			throw new Exception_Form();
		}
	}

	/**
	 * Проверить поле
	 * 
	 * @param string $value
	 * @param string $type
	 * @param bool $empty_allow
	 * @param string $identified
	 * @param string $name
	 */
	public static function check_field(&$value, $type, $empty_allow, $identified, $name)
	{
		$value = trim((string) $value);
		if ($value === "")
		{
			if (!$empty_allow)
			{
				self::add("Поле «{$name}» не заполнено.", $identified);
			}
		}
		else
		{
			if (!Chf::$type($value))
			{
				self::add("Поле «{$name}» задано неверно. " . Chf::error(), $identified);
			}
		}
	}
}
?>
