<?php
/**
 * Определение маршрута
 */
try
{
	/*--------------- Разбор урла -----------------*/
	if(Reg::url_end() !== "" and Reg::url_path() !== "/")
	{
		/* Если урл заканчивается не на «окончание урла (url_end)» то 404 */
		if(mb_substr(Reg::url_path(), 0 - mb_strlen(Reg::url_end())) !== Reg::url_end())
		{throw new Exception("page_404");}

		/* Новые глобальные переменные с учётом «окончания урла» */
		Reg::url_path( mb_substr(Reg::url_path(), 0, mb_strlen(Reg::url_path()) - mb_strlen(Reg::url_end())) );
		Reg::url_path_ar(explode("/", mb_substr(Reg::url_path(), 1)));
	}

	/* Если один из элементов урла пустой то 404 */
	foreach (Reg::url_path_ar() as $val)
	{
		if(trim($val) === "")
		{throw new Exception("page_404");}
		
		if(!Chf::url($val))
		{throw new Exception("page_404");}
	}
	
	/*--------------- Главная страница -----------------*/
	if(Reg::url_path() === "/")
	{throw new Exception("page_home");}
	
	/*--------------- Другие страницы ------------------*/
	/* Модули имеющие страницы */
	$query = 
<<<SQL
SELECT 
	"ID",
	"Identified", 
	"Type"
FROM 
	"module"
WHERE 
	"Pages_Isset" = true AND
	"Active" = true
ORDER BY 
	"Identified" ASC
SQL;
	$module = Reg::db_core()->query_assoc($query, null, "module");
	
	if(empty($module))
	{throw new Exception("page_404");}

	/* Поиск маршрута */
	$route = false;
	foreach ($module as $val)
	{
		if(!is_file(Reg::path_app() . "/{$val['Type']}/{$val['Identified']}/route.php"))
		{continue;}

		$return = call_user_func(function($val)
		{
			return require Reg::path_app() . "/{$val['Type']}/{$val['Identified']}/route.php";
		}, $val);

		if(!empty($return) and is_string($return))
		{
			Reg::module($val['Identified']);
			Reg::exe($return);
			$route = true;
			break;
		}
	}
	
	if($route === false)
	{throw new Exception("page_404");}
	
	/* Тип страницы */
	Reg::page_type("module");
}
catch (Exception $e)
{
	/*--------------- Главная страница -----------------*/
	if($e->getMessage() === "page_home")
	{
		Reg::page_type("home");
		
		Reg::module(P::get("default_module"));
		Reg::exe(P::get("default_exe"));
	}
	/*---------------- Страница 404 -------------*/
	elseif($e->getMessage() === "page_404")
	{
		Reg::page_type("404");
		
		Reg::module(P::get("404_module"));
		Reg::exe(P::get("404_exe"));
		
		/* Заголовок */
		header("HTTP/1.0 404 Not Found");
	}
	/* Другое исключение */
	else
	{
		echo $e->__toString();
	}
}
?>