<?php
Err::check_field($_POST['Title'], "string", true, "Title", "Заголовок");
Err::check_field($_POST['Content'], "html", true, "Content", "Содержание");
Err::check_field($_POST['Tags'], "tags", true, "Tags", "Тэги");
Err::exception();

P::set("_service", "404_title", $_POST['Title']);
T::set("_service", "404_content", $_POST['Content']);
P::set("_service", "404_tags", $_POST['Tags']);

G::version()->set
(
	"_service/404", 
	[
		"Title" => $_POST['Title'],
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	]
);

mess_ok("«Страница 404» сохранена");
?>