<?php
/**
 * Загрузка шаблона
 */

/* Основные переменные для шаблона */
if(Reg::module() === "" or Reg::exe() === "")
{
	if(Reg::page_type() === "403")
	{
		Reg::title(P::get("403_title"));
		Reg::content(T::get("403_content"));
	}
	elseif(Reg::page_type() === "404")
	{
		Reg::title(P::get("404_title"));
		Reg::content(T::get("404_content"));
	}
}

/* Загружаем шаблон */
ob_start();
require Reg::path_app() . "/html/" . Reg::html() . ".html";
Reg::output(ob_get_contents());
ob_end_clean();

/* Заменяем комментарии */
$search = 
[
	"<!--zn_title-->",
	"<!--zn_content-->"
];

$replace = 
[
	Reg::title(),
	Reg::content()
];

foreach (Reg::inc() as $key=>$val)
{
	$search[] = $key;
	$replace[] = $val;
}

Reg::output(str_replace($search, $replace, Reg::output()));
?>