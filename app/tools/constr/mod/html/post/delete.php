<?php
$html = _Html::remove($_GET['id']);

mess_ok("Шаблон «{$html['Identified']} ({$html['Name']}) удалён.»");
redirect("#html/list");
?>