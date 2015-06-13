<?php
$faq = Faq::delete($_GET['id']);
G::version()->delete("faq/" . $faq['ID']);

mess_ok("Вопрос с ответом «" . mb_substr($faq['Question'], 0, 40) . "» удалён.");
redirect("#faq/list");
?>