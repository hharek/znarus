<?php
G::file()->put("robots.txt", $_POST['Content']);

mess_ok("Файл «robots.txt» отредактирован.");
reload();
?>