<?php
Err::check_field($_POST['Title'], "string", true, "Title", "Заголовок");
Err::check_field($_POST['Content'], "html", true, "Content", "Содержание");
Err::check_field($_POST['Tags'], "tags", true, "Tags", "Тэги");
Err::check_field($_POST['Html'], "identified", true, "Html", "Шаблон");
Err::exception();

P::set("page", "403_title", $_POST['Title']);
T::set("page", "403_content", $_POST['Content']);
P::set("page", "403_tags", $_POST['Tags']);
P::set("page", "403_html", $_POST['Html']);

G::version()->set
(
	"page/page_403", 
	[
		"Title" => $_POST['Title'],
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	]
);

mess_ok("«Страница с кодом 403» сохранена");

_Cache_Front::delete(["module" => "page"]);
?>