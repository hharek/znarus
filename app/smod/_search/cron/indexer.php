<?php
/* Инициализация */
require __DIR__ . "/../../../conf/conf.php";
require __DIR__ . "/../../../conf/options.php";
require __DIR__ . "/../../../conf/ini.php";
require __DIR__ . "/../../../sys/g.php";
require __DIR__ . "/../../../init.php";

/* Создать индекс */
_Search::create_index();
?>