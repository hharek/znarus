<?php
$faq = Faq::delete($_GET['id']);

mess_ok("Вопрос с ответом «" . mb_substr($faq['Question'], 0, 40) . "» удалён.");
redirect("#faq/list");
?>