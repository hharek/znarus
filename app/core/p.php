<?php
/**
 * Выборка и изменение параметров
 */
class P
{
	/**
	 * Получить значение параметра
	 * 
	 * @return mixed
	 */
	public static function get()
	{
		/* Аргументы */
		if(func_num_args() === 1)
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
		
		/* Значение  */
		$param = self::select_line_by_identified($module_identified, $param_identified);
		$value = $param['Value'];
		switch ($param['Type'])
		{
			case "string":
			{$value = (string)$value;}
			break;
		
			case "int":
			{$value = (int)$value;}
			break;
		
			case "bool":
			{$value = (bool)$value;}
			break;
		}
		
		return $value;
	}
	
	/**
	 * Назначить значение параметра
	 */
	public static function set()
	{
		/* Аргументы */
		if(func_num_args() === 2)
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
		$param = self::select_line_by_identified($module_identified, $param_identified);
		
		switch ($param['Type'])
		{
			case "string":
			{
				if(mb_strlen($value) !== 0)
				{
					if(!Chf::string($value))
					{throw new Exception("Значение параметра задан неверно. ".Chf::error());}
				}
			}
			break;
		
			case "int":
			{
				if(!Chf::int($value))
				{throw new Exception("Значение параметра задан неверно. ".Chf::error());}
			}
			break;
		
			case "bool":
			{
				if(!Chf::bool($value))
				{throw new Exception("Значение параметра задан неверно. ".Chf::error());}
			}
			break;
		}
		
		/* SQL */
		$data =
		[
			"Value" => $value
		];
		Reg::db_core()->update("param", $data, array("ID" => $param['ID']));
	}
	
	/**
	 * Получить данные по параметру
	 * 
	 * @param string $module_identified
	 * @param string $param_identified
	 * @return array
	 */
	private static function select_line_by_identified($module_identified, $param_identified)
	{
		/* Проверка */
		if($module_identified !== "sys")
		{
			if(!Chf::identified($module_identified))
			{throw new Exception("Идентификатор у модуля задан неверно. ".Chf::error());}
		}
		
		if(!Chf::identified($param_identified))
		{throw new Exception("Идентификатор у параметра задан неверно. ".Chf::error());}
		
		/* SQL */
		if($module_identified !== "sys")
		{
			$query = 
<<<SQL
SELECT 
	"p"."ID", 
	"p"."Name", 
	"p"."Identified", 
	"p"."Type", 
	"p"."Value"
FROM 
	"param" as "p", 
	"module" as "m"
WHERE 
	"p"."Module_ID" = "m"."ID" AND 
	"m"."Identified" = $1 AND 
	"p"."Identified" = $2
SQL;
			$param = Reg::db_core()->query_line($query, [$module_identified, $param_identified], ["param", "module"]);
		}
		else
		{
			$query = 
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Identified", 
	"Type", 
	"Value"
FROM 
	"param"
WHERE 
	"Module_ID" IS NULL AND 
	"Identified" = $1
SQL;
			$param = Reg::db_core()->query_line($query, $param_identified, "param");
		}
		
		if(empty($param))
		{throw new Exception("Параметра «{$module_identified}» - «{$param_identified}» не существует.");}
		
		return $param;
	}
}
?>
