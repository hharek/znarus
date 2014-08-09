<?php
$faq = Faq::add($_POST['Question'], $_POST['Answer']);

mess_ok("Вопрос с ответом «" . mb_substr($faq['Question'], 0, 40) . "» добавлен.");
redirect("#faq/list");
?>