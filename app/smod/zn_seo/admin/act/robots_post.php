<?php
Reg::file()->put("robots.txt", $_POST['content']);

mess_ok("Файл «robots.txt» отредактирован.");
reload();
?>