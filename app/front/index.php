<?php
//header("Content-type: text/plain");

require "01_init.php";
require "02_proc_start.php";
require "03_router.php";
require "04_access.php";
require "05_html_set.php";
require "06_inc.php";
require "07_exe.php";
require "08_html.php";
require "09_proc_end.php";
require "10_data.php";

echo Reg::output();
?>