<?php
$faq = Faq::add($_POST['Question'], $_POST['Answer']);
G::version()->set
(
	"faq/" . $faq['ID'], 
	[
		"Question" => $_POST['Question'],
		"Answer" => $_POST['Answer']
	]
);
G::draft()->delete("faq/add");

mess_ok("Вопрос с ответом «" . mb_substr($faq['Question'], 0, 40) . "» добавлен.");
redirect("#faq/list");
?>