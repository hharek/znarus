<?php
Err::check_field($_POST['Title'], "string", true, "Title", "Заголовок");
Err::check_field($_POST['Content'], "html", true, "Content", "Содержание");
Err::check_field($_POST['Tags'], "tags", true, "Tags", "Тэги");
Err::exception();

P::set("_service", "403_title", $_POST['Title']);
T::set("_service", "403_content", $_POST['Content']);
P::set("_service", "403_tags", $_POST['Tags']);

G::version()->set
(
	"_service/403", 
	[
		"Title" => $_POST['Title'],
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	]
);

mess_ok("«Страница 403» сохранена");
?>