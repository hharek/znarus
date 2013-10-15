<?php
$html = ZN_Html::delete($_GET['id']);

mess_ok("Шаблон «{$html['Identified']} ({$html['Name']}) удалён.»");
redirect("#html/list");
menu_top("html");
?>