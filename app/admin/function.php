<?php
/**
 * Сменить заголовок
 * 
 * @param string $title
 */
function title($title)
{
	if(mb_strlen($title) === 0)
	{throw new Exception("Заголовок не задан.");}
	
	Reg::title($title);
}

/**
 * Показать путь
 * 
 * @param array $path - ["Путь 1 [#url1]", "Путь 2 [#url2]"]
 */
function path($path)
{
	if(empty($path))
	{throw new Exception("Путь не задан.");}
	
	if(!is_array($path) and is_string($path))
	{
		$path = array($path);
	}
	
	Reg::path($path);
}

/**
 * Показать сообщение об успешном выполнении
 * 
 * @param string $mess
 */
function mess_ok($mess)
{
	if(empty($mess))
	{throw new Exception("Сообщение об успешном выполнении не задано.");}
	
	Reg::mess_ok($mess);
}

/**
 * Переход на другую страницу
 * 
 * @param string $url
 */
function redirect($url)
{
	if(empty($url))
	{throw new Exception("Урл для редиректа не задан.");}
	
	Reg::redirect($url);
}
?>