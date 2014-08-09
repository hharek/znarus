<?php
$faq = Faq::edit($_GET['id'], $_POST['Question'], $_POST['Answer']);

mess_ok("Вопрос с ответом «" . mb_substr($faq['Question'], 0, 40) . "» отредактирован.");
?>