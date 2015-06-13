<?php
/* Инициализация */
require __DIR__ . "/../../../conf/conf.php";
require __DIR__ . "/../../../conf/options.php";
require __DIR__ . "/../../../conf/ini.php";
require __DIR__ . "/../../../sys/g.php";
require __DIR__ . "/../../../init.php";

/* Удалить кэш страницы «/карта-сайта» */
_Cache_Front::delete(["module" => "_sitemap"]);

/* Зайти на страницу чтобы создать кэш страницы */
$protocol = "http";
if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === "on")
{
	$protocol = "https";
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $protocol . "://" . DOMAIN . "/карта-сайта");
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec($ch);
curl_close($ch);
?>