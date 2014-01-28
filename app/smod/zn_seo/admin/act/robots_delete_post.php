<?php
Reg::file()->rm("robots.txt");

mess_ok("Файл «robots.txt» удалён.");
reload();
?>