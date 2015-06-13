<?php
$html = _Html::add($_POST['Name'], $_POST['Identified']);

mess_ok("Шаблон «{$html['Identified']} ({$html['Name']})» добавлен.");
redirect("#html/list");
?>