<?php
/* Инициализация */
require __DIR__ . "/../../../conf/conf.php";
require __DIR__ . "/../../../conf/options.php";
require __DIR__ . "/../../../conf/ini.php";
require __DIR__ . "/../../../sys/g.php";
require __DIR__ . "/../../../init.php";

/* Создать индекс */
if (P::get("_search", "type") === "pgsql")
{
	_Search::create_index();
}
elseif (P::get("_search", "type") === "sphinx")
{
	$protocol = "http";
	if (HTTPS_ENABLE === true)
	{
		$protocol = "https";
	}
	
	echo "Чтобы создать индекс для сфинкса используете «curl {$protocol}://" . DOMAIN . "/" . URL_AJAX . "/_search/indexer» .\n" ;
}
?>