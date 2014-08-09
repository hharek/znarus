<?php
$faq = Faq::select_line_by_id($_GET['id']);

title("Вопрос с ответом «" . mb_substr($faq['Question'], 0, 40) . "»");
path
([
	"Вопросы с ответами [#faq/list]",
	mb_substr($faq['Question'], 0, 40) . " [#faq/edit?id={$faq['ID']}]"
]);

?>