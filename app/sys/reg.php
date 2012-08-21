<?php

/**
 * Глобальный реестр
 */
class Reg
{

    /**
     * Массив параметров
     * 
     * @var array
     */
    public static $_data = array();

    /**
     * Список константов
     * 
     * @var array
     */
    private static $_constant = array();

    /**
     * Функции
     * 
     * @param string $func
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($func, $args)
    {
        /* Проверка */
        $func = (string) $func;
        self::_check_name($func);

        /* Удалить */
        if ($func == "_unset")
        {
            return self::_unset($args);
        }

        /* Проверка на существование */
        if ($func == "_isset")
        {
            if (count($args) != 1)
            {
                throw new Exception("Аргументы заданы неверно.");
            }

            return self::_isset($args[0]);
        }

        if (count($args) > 2)
        {
            throw new Exception("Аргументы заданы неверно.");
        }

        /* Создать параметр */
        if (!empty($args) and count($args) == 1)
        {
            return self::_create($func, $args[0]);
        }

        /* Создать константу */
        if (!empty($args) and count($args) == 2)
        {
            if (is_bool($args))
            {
                throw new Exception("Второй аргумент задан неверно.");
            }

            return self::_create_constant($func, $args[0]);
        }

        /* Прочитать */
        if (empty($args))
        {
            if (!isset(self::$_data[$func]))
            {
                throw new Exception("Параметра \"{$func}\" не существует.");
            }

            return self::$_data[$func];
        }
    }

    /**
     * Создать параметр
     * 
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    private static function _create($name, &$value)
    {
        if (in_array($name, self::$_constant))
        {
            throw new Exception("Невозможно изменить константу.");
        }

        self::$_data[$name] = $value;

        return true;
    }

    /**
     * Создать константу
     * 
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    private static function _create_constant($name, &$value)
    {
        if (in_array($name, self::$_constant))
        {
            throw new Exception("Невозможно изменить константу.");
        }

        if (isset(self::$_data[$name]))
        {
            throw new Exception("Невозможно параметр назначить как константу.");
        }

        self::$_data[$name] = $value;
        self::$_constant[] = $name;

        return true;
    }

    /**
     * Удалить параметр
     * 
     * @param array $args
     * @return bool
     */
    private static function _unset($args)
    {
        if (empty($args))
        {
            throw new Exception("Не указаны параметры для удаления.");
        }

        foreach ($args as $key => $val)
        {
            if (!isset(self::$_data[$val]))
            {
                throw new Exception("Параметра \"{$val}\" не существует.");
            }

            if (in_array($val, self::$_constant))
            {
                throw new Exception("Невозможно удалить константу \"{$val}\".");
            }
        }

        foreach ($args as $key => $val)
        {
            unset(self::$_data[$val]);
        }

        return true;
    }

    /**
     * Проверить параметр на существование
     * 
     * @param string $name
     * @return bool
     */
    private static function _isset($name)
    {
        return isset(self::$_data[$name]);
    }

    /**
     * Проверить имя
     * 
     * @param string $name
     * @return bool 
     */
    private static function _check_name($name)
    {
        /* Пустая строка */
        $name = trim($name);
        if (empty($name))
        {
            throw new Exception("Имя параметра задано неверно.");
        }

        /* Нулевой символ */
        $strlen_before = strlen($name);
        $name = str_replace(chr(0), '', $name);
        $strlen_after = strlen($name);
        if ($strlen_before != $strlen_after)
        {
            throw new Exception("Имя параметра задано. Нулевой символ.");
        }

        /* Очень большая строка */
        if (strlen($name) > 127)
        {
            throw new Exception("Имя параметра задано. Очень большая строка.");
        }

        /* Недопустимые символы */
        $name = strtr($name, "abcdefghijklmnopqrstuvwxyz_0123456789", "                                     ");
        if (strlen(trim($name)) != 0)
        {
            throw new Exception("Имя параметра задано неверно. Недопустимые символы, разрешено (a-zA-Z0-9_)");
        }

        return true;
    }
	
	/**
	 * @return ZN_Pgsql
	 */
	private static function db(){}
	
	/**
	 * @return ZN_Pgsql
	 */
	private static function db_core(){}
	
	/**
	 * @return ZN_Pgsql
	 */
	private static function db_creator(){}
}

?>
