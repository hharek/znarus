<?php
$faq = Faq::get($_GET['id']);

title("Вопрос с ответом «" . mb_substr($faq['Question'], 0, 40) . "»");
path
([
	"Вопросы с ответами [#faq/list]",
	mb_substr($faq['Question'], 0, 40)
]);
	
version("faq/". $faq['ID']);
autosave();
?>