<?php
title("Управление");
path(["Управление"]);

/* Разбор урла */
$url = "/";
if(isset($_GET['url']))
{
	$url = $_GET['url']; 
}

if(mb_substr($url, 0, 1) !== "/")
{
	$url = "/" . $url; 	
}

/* Подготовка урла к запросу */
$protocol = "http";
if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === "on")
{
	$protocol = "https";
}
$url_full = $protocol . "://" . DOMAIN . $url;
$parse_url = parse_url($url_full);
if(isset($parse_url['query']))
{
	$url_full .= "&"; 
}
else
{
	$url_full .= "?"; 
}
$url_full .= FRONT_INFO_GET . "={$_COOKIE['_sid']}";

/* Запрос на страницу и получения данных */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_full);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
$data = curl_exec($ch);
$data = json_decode($data, true);
curl_close($ch);

/* Данные */
$html = $data['html'];
$inc = $data['inc'];
$html_part = $data['html_part'];
$module_exe = $data['module_exe'];
$exe = $data['exe'];

?>