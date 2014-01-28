<?php
/**
 * Выбор шаблона
 */

try
{
	/* Модуль и исполнитель не указан */
	if(Reg::module() === "" or Reg::exe() === "")
	{throw new Exception("html_default");}
	
	/* Тип модуля */
	if(mb_substr(Reg::module(), 0, 3) === "zn_")
	{Reg::module_type("smod");}
	else
	{Reg::module_type("mod");}
	
	/* Файл html_set.php не найден */
	if(!is_file(Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::module() . "/html_set.php"))
	{throw new Exception("html_default");}
	
	/* Загрузка html_set.php */
	$return = require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::module() . "/html_set.php";
	
	/* Файл не вернул строку */
	if(empty($return) or !is_string($return))
	{throw new Exception("html_default");}
	
	Reg::html($return);
}
catch (Exception $e)
{
	/* Шаблон по умолчанию */
	if($e->getMessage() === "html_default")
	{
		Reg::html(P::get("html_default"));
	}
	/* Другое исключение */
	else
	{
		echo $e->__toString();
	}
}
?>