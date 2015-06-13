<?php
$html = _Html::delete($_GET['id']);

mess_ok("Шаблон «{$html['Identified']} ({$html['Name']}) удалён.»");
redirect("#html/list");
?>