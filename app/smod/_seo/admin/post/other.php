<?php
/* Удалить кэш страниц */
if 
(
	P::get("_seo", "home_title") !== $_POST['Home_Title'] or
	T::get("_seo", "home_description") !== $_POST['Home_Description'] or
	P::get("_seo", "home_keywords") !== $_POST['Home_Keywords']
)
{	
	G::cache_page()->delete("other_home");
}

if 
(
	P::get("_seo", "404_title") !== $_POST['404_Title'] or
	T::get("_seo", "404_description") !== $_POST['404_Description'] or
	P::get("_seo", "404_keywords") !== $_POST['404_Keywords']
)
{	
	G::cache_page()->delete("other_404");
}

if 
(
	P::get("_seo", "403_title") !== $_POST['403_Title'] or
	T::get("_seo", "403_description") !== $_POST['403_Description'] or
	P::get("_seo", "403_keywords") !== $_POST['403_Keywords']
)
{	
	G::cache_page()->delete("other_403");
}

/* Назначить данные */
P::set("_seo", "home_title", $_POST['Home_Title']);
T::set("_seo", "home_description", $_POST['Home_Description']);
P::set("_seo", "home_keywords", $_POST['Home_Keywords']);

P::set("_seo", "404_title", $_POST['404_Title']);
T::set("_seo", "404_description", $_POST['404_Description']);
P::set("_seo", "404_keywords", $_POST['404_Keywords']);

P::set("_seo", "403_title", $_POST['403_Title']);
T::set("_seo", "403_description", $_POST['403_Description']);
P::set("_seo", "403_keywords", $_POST['403_Keywords']);

/* Сообщение об удачном обновлении */
mess_ok("Сохранено");
?>