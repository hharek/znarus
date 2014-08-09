<?php
/* Заголовок */
Reg::title("Поиск");
Reg::meta_title("Поиск по сайту");
Reg::meta_description("Здесь вы можете воспользоваться поиском чтобы найти необходимую информацию.");
Reg::meta_keywords("поиск по сайту, найти, поиск");
Reg::path
([
	"Поиск [/поиск]"
]);

/* Слово для поиска */
$word = "";
if(!empty($_GET['word']))
{
	$word = $_GET['word'];
	$word = preg_replace("#[^0-9a-zа-яё\-\_\. ]#isu", "", $word);
	$word = preg_replace("#[ ]{2,}#isu", " ", $word);
	$word = trim($word);
	$word = mb_strtolower($word);
	
	Reg::meta_title("Поиск по слову «{$word}»");
	Reg::path
	([
		"Поиск [/поиск]",
		"{$word} [/поиск?word={$word}]"
	]);
}

if(empty($word))
{ return true; }

/* Текущая страница */
$page = 1;
if(!empty($_GET['page']))
{
	$page = (int)$_GET['page'];
}

/* Поиск */
require Reg::path_app() . "/lib/sphinxapi/sphinxapi.php";
$sph = new SphinxClient();
$sph->SetServer("127.0.0.1", 9312);
$sph->SetMatchMode(SPH_MATCH_ANY);
$sph->SetLimits(($page-1) * P::get("zn_sphinx", "count_to_page"), P::get("zn_sphinx", "count_to_page"));
$sph_result = $sph->Query($word, "example");
if($sph_result === false or empty($sph_result['matches']))
{ return false; }

/* Страницы */
$page_all = ceil($sph_result['total_found'] / P::get("zn_sphinx", "count_to_page"));

/* Обработка */
$result = [];
foreach ($sph_result['matches'] as $val)
{
	/* Урл */
	$url = $val['attrs']['url'];
	
	/* Наименование */
	$name = $val['attrs']['name'];
	$name = str_replace("\n", " ", $name);
	$name = html_entity_decode($name, ENT_QUOTES, "UTF-8");
	$name = strip_tags($name);
	$name = preg_replace("#[^0-9a-zа-яё\-\_\.:; ]#isu", "", $name);
	$name = preg_replace("#[ ]{2,}#isu", " ", $name);
	
	/* Содержание */
	$content = $val['attrs']['content'];
	$content = str_replace("\n", " ", $content);
	$content = html_entity_decode($content, ENT_QUOTES, "UTF-8");
	$content = strip_tags($content);
	$content = preg_replace("#[^0-9a-zа-яё\-\_\.:; ]#isu", "", $content);
	$content = preg_replace("#[ ]{2,}#isu", " ", $content);
	$content = mb_substr($content, 0, 400);
	
	$result[] = 
	[
		"url" => $url,
		"name" => $name,
		"content" => $content
	];
}



?>