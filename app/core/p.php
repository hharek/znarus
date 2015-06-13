<?php
/**
 * Выборка и изменение параметров
 */
class P
{
	/**
	 * Загруженные параметры
	 * 
	 * @var array
	 */
	private static $_load = [];

	/**
	 * Получить значение параметра
	 * 
	 * @return mixed
	 */
	public static function get()
	{
		/* Аргументы */
		if (func_num_args() === 1)
		{
			$module_identified = "sys";
			$param_identified = func_get_arg(0);
		}
		elseif (func_num_args() === 2)
		{
			$module_identified = func_get_arg(0);
			$param_identified = func_get_arg(1);
		}
		else
		{
			throw new Exception("Необходимо указать один или два аргумента");
		}
		
		/* Если уже параметр загружался */
		$key = $module_identified . "_" . $param_identified;
		if (isset(self::$_load[$key]))
		{
			return self::$_load[$key];
		}

		/* Значение  */
		$param = self::_get_by_identified($module_identified, $param_identified);
		$value = $param['Value'];
		switch ($param['Type'])
		{
			case "string":
			{
				$value = (string) $value;
			}
			break;

			case "int":
			{
				$value = (int) $value;
			}
			break;

			case "bool":
			{
				$value = (bool) $value;
			}
			break;
		}
		
		/* Поместить в загруженные */
		$key = $module_identified . "_" . $param_identified;
		self::$_load[$key] = $value;

		return $value;
	}

	/**
	 * Назначить значение параметра
	 */
	public static function set()
	{
		/* Аргументы */
		if (func_num_args() === 2)
		{
			$module_identified = "sys";
			$param_identified = func_get_arg(0);
			$value = func_get_arg(1);
		}
		elseif (func_num_args() === 3)
		{
			$module_identified = func_get_arg(0);
			$param_identified = func_get_arg(1);
			$value = func_get_arg(2);
		}
		else
		{
			throw new Exception("Необходимо указать два или три аргумента");
		}

		/* Проверка */
		$param = self::_get_by_identified($module_identified, $param_identified);

		switch ($param['Type'])
		{
			case "string":
			{
				if (mb_strlen($value) !== 0)
				{
					if (!Chf::string($value))
					{
						throw new Exception("Значение параметра задан неверно. " . Chf::error());
					}
				}
			}
			break;

			case "int":
			{
				if (!Chf::int($value))
				{
					throw new Exception("Значение параметра задан неверно. " . Chf::error());
				}
			}
			break;

			case "bool":
			{
				if (!Chf::bool($value))
				{
					throw new Exception("Значение параметра задан неверно. " . Chf::error());
				}
			}
			break;
		}

		/* SQL */
		$data = 
		[
			"Value" => $value
		];
		G::db_core()->update("param", $data, ["ID" => $param['ID']]);
		
		/* Поместить в загруженные */
		$key = $module_identified . "_" . $param_identified;
		self::$_load[$key] = $value;
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("param");
	}

	/**
	 * Получить данные по параметру
	 * 
	 * @param string $module_identified
	 * @param string $param_identified
	 * @return array
	 */
	private static function _get_by_identified($module_identified, $param_identified)
	{
		/* Проверка */
		if (!Chf::identified($module_identified))
		{
			throw new Exception("Идентификатор модуля задан неверно. " . Chf::error());
		}
		
		if (!Chf::identified($param_identified))
		{
			throw new Exception("Идентификатор параметра задан неверно. " . Chf::error());
		}

		/* Значение */
		$param = G::cache_db_core()->get("param_get_" . $module_identified . "_" . $param_identified);
		if ($param === null)
		{
			$param = G::db_core()->param_get($module_identified, $param_identified)->row();
			if (empty($param))
			{
				throw new Exception("Параметра «{$module_identified}» - «{$param_identified}» не существует.");
			}
			G::cache_db_core()->set("param_get_" . $module_identified . "_" . $param_identified, $param, "param");
		}
		
		return $param;
	}
}

?>
