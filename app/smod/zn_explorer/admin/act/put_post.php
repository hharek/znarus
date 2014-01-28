<?php
Reg::file()->put($_POST['file'], $_POST['content']);

mess_ok("Файл «{$_POST['file']}» отредактирован");
?>