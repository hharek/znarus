<?php
/**
 * Основной вывод
 */
try 
{
	/* Исполнитель не определён */
	if(Reg::module() === "" or Reg::exe() === "")
	{throw new Exception("not_exe");}
		
	/* Выполение исполнителя */
	ob_start();
	call_user_func(function ()
	{
		/* Классы */
		$path = Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::module() . "/class";
		$files = scandir($path);
		foreach ($files as $val)
		{
			if (is_file($path . "/" . $val) and mb_substr($val, -4) === ".php")
			{require_once $path . "/" . $val;}
		}
		
		/* Сам код */
		require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::module() . "/exe/act/" . Reg::exe() . ".php";
		require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::module() . "/exe/html/" . Reg::exe() . ".html";
	});
	Reg::content(ob_get_contents());
	ob_end_clean();
}
catch (Exception $e)
{
	/* Исполнитель не определён */
	if($e->getMessage() === "not_exe")
	{
		
	}
	/* Другое исключение */
	else
	{
		echo $e->__toString();
	}
}

?>