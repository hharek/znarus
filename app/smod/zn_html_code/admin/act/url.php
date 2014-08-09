<?php
/* Разбор урла */
$url = "/";
if(isset($_GET['url']))
{ $url = $_GET['url']; }

if( mb_substr($url, 0, 1) !== "/" )
{ $url = "/" . $url; }

/* Подготовка урла к запросу */
$url_full = "http://" . Reg::domain() . $url;
$parse_url = parse_url($url_full);
if(isset($parse_url['query']))
{ $url_full .= "&"; }
else
{ $url_full .= "?"; }
$url_full .= "zn={$_COOKIE['sid']}&serialize";

/* Запрос на страницу и получения данных */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_full);
curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (X11; Linux i686) Presto/2.12.388 Version/12.16");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = unserialize(curl_exec($ch));
curl_close($ch);

/* Шаблон */
$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Identified"
FROM 
	"html"
WHERE 
	"Identified" = $1
SQL;
$html = Reg::db_core()->query_line($query, $data['html'], "html");

/* Inc */
$inc_id = [];
foreach ($data['inc'] as $val)
{
	$inc_id[] = $val['ID'];
}
$inc_id_str = "'" . implode("','", $inc_id) . "'";

$query =
<<<SQL
SELECT
	"i"."ID",
	"i"."Name",
	"i"."Identified",
	"m"."ID" as "Module_ID",
	"m"."Name" as "Module_Name",
	"m"."Identified" as "Module_Identified"
FROM 
	"inc" as "i",
	"module" as "m"
WHERE 
	"i"."ID" IN ({$inc_id_str}) AND
	"i"."Module_ID" = "m"."ID"
SQL;
$inc = Reg::db_core()->query_assoc($query, null, "table");

/* Exe */
$query = 
<<<SQL
SELECT
	"e"."ID",
	"e"."Name",
	"e"."Identified",
	"m"."ID" as "Module_ID",
	"m"."Name" as "Module_Name",
	"m"."Identified" as "Module_Identified"
FROM 
	"exe" as "e",
	"module" as "m"
WHERE 
	"e"."Identified" = $1 AND
	"e"."Module_ID" = "m"."ID" AND
	"m"."Identified" = $2
SQL;
$exe = Reg::db_core()->query_line($query, [$data['exe'], $data['module']], "exe");

?>