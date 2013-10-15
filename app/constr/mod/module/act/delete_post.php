<?php
$module = ZN_Module::delete($_GET['id']);

mess_ok("Модуль «{$module['Identified']} ({$module['Name']})» удалён.");
redirect("#module/list");
menu_top("module");
?>