<?php
Err::check_field($_POST['Title'], "string", true, "Title", "Заголовок");
Err::check_field($_POST['Content'], "html", true, "Content", "Содержание");
Err::check_field($_POST['Tags'], "tags", true, "Tags", "Тэги");
Err::check_field($_POST['Html'], "identified", true, "Html", "Шаблон");
Err::exception();

P::set("page", "home_title", $_POST['Title']);
T::set("page", "home_content", $_POST['Content']);
P::set("page", "home_tags", $_POST['Tags']);
P::set("page", "home_html", $_POST['Html']);

G::version()->set
(
	"page/page_home", 
	[
		"Title" => $_POST['Title'],
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	]
);

mess_ok("«Главная страница» сохранена");

_Cache_Front::delete(["module" => "page"]);
?>