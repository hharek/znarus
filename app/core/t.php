<?php
/**
 * Выборка и изменение текстов
 */
class T
{
	/**
	 * Получить текст
	 * 
	 * @return mixed
	 */
	public static function get()
	{
		/* Аргументы */
		if(func_num_args() === 1)
		{
			$module_identified = "sys";
			$text_identified = func_get_arg(0);
		}
		elseif (func_num_args() === 2) 
		{
			$module_identified = func_get_arg(0);
			$text_identified = func_get_arg(1);
		}
		else 
		{
			throw new Exception("Необходимо указать один или два аргумента");
		}
		
		/* Значение  */
		$text = self::select_line_by_identified($module_identified, $text_identified);
		
		return $text['Value'];
	}
	
	/**
	 * Назначить новый текст
	 */
	public static function set()
	{
		/* Аргументы */
		if(func_num_args() === 2)
		{
			$module_identified = "sys";
			$text_identified = func_get_arg(0);
			$value = func_get_arg(1);
		}
		elseif (func_num_args() === 3) 
		{
			$module_identified = func_get_arg(0);
			$text_identified = func_get_arg(1);
			$value = func_get_arg(2);
		}
		else 
		{
			throw new Exception("Необходимо указать два или три аргумента");
		}
		
		/* Проверка */
		$text = self::select_line_by_identified($module_identified, $text_identified);
		
		/* SQL */
		$data =
		[
			"Value" => $value
		];
		Reg::db_core()->update("text", $data, array("ID" => $text['ID']));
	}
	
	/**
	 * Получить данные по тексту
	 * 
	 * @param string $module_identified
	 * @param string $text_identified
	 * @return array
	 */
	private static function select_line_by_identified($module_identified, $text_identified)
	{
		/* Проверка */
		if($module_identified !== "sys")
		{
			if(!Chf::identified($module_identified))
			{throw new Exception("Идентификатор у модуля задан неверно. ".Chf::error());}
		}
		
		if(!Chf::identified($text_identified))
		{throw new Exception("Идентификатор у текста задан неверно. ".Chf::error());}
		
		/* SQL */
		if($module_identified !== "sys")
		{
			$query = 
<<<SQL
SELECT 
	"t"."ID", 
	"t"."Name", 
	"t"."Identified", 
	"t"."Value"
FROM 
	"text" as "t", 
	"module" as "m"
WHERE 
	"t"."Module_ID" = "m"."ID" AND 
	"m"."Identified" = $1 AND 
	"t"."Identified" = $2
SQL;
			$text = Reg::db_core()->query_line($query, [$module_identified, $text_identified], ["text", "module"]);
		}
		else
		{
			$query = 
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Identified", 
	"Value"
FROM 
	"text"
WHERE 
	"Module_ID" IS NULL AND 
	"Identified" = $1
SQL;
			$text = Reg::db_core()->query_line($query, $text_identified, "text");
		}
		
		if(empty($text))
		{throw new Exception("Текста «{$module_identified}» - «{$text_identified}» не существует.");}
		
		return $text;
	}
}
?>