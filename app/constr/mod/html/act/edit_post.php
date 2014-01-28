<?php
$html = ZN_Html::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Default']);

mess_ok("Шаблон «{$html['Identified']} ({$html['Name']})» отредактирован.");
require "edit.php";
menu_top("html");
?>