<?php
$html = ZN_Html::select_line_by_id($_GET['id']);
Reg::file_app()->put("html/{$html['Identified']}.html", $_POST['Content']);

mess_ok("Шаблон «{$html['Identified']}.html отредактирован.»");
//reload();
?>