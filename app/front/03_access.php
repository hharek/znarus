<?php
/**
 * Разрешён ли доступ к странице
 */

/* Все модули */
try
{
	/* Страница уже определена как 404 */
	if(Reg::page_type() === "404")
	{throw new Exception("page_404");}
	
	$query =
<<<SQL
SELECT
	"ID",
	"Identified",
	"Type"
FROM 
	"module"
ORDER BY 
	"Identified"
SQL;
	$module = Reg::db_core()->query_assoc($query, null, "module");

	/* Поиск файла access.php */
	$access = true;
	foreach ($module as $val)
	{
		if(!is_file(Reg::path_app() . "/{$val['Type']}/{$val['Identified']}/access.php"))
		{continue;}

		$return = call_user_func(function($val)
		{
			return require Reg::path_app() . "/{$val['Type']}/{$val['Identified']}/access.php";
		}, $val);

		if($return === false)
		{
			throw new Exception("page_403");
			break;
		}
	}
}
catch (Exception $e)
{
	/* Доступ запрещён */
	if($e->getMessage() === "page_403")
	{
		Reg::page_type("403");
		
		Reg::module(P::get("403_module"));
		Reg::exe(P::get("403_exe"));
		
		/* Заголовок */
		header("HTTP/1.0 403 Forbidden");
	}
	/* Страница 404 */
	elseif($e->getMessage() === "page_404")
	{}
	/* Другое исключение */
	else
	{
		echo $e->__toString();
	}
}
?>