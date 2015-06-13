<?php
$html = _Html::edit($_GET['id'], $_POST['Name'], $_POST['Identified']);

/* Назначить шаблоном по умолчанию */
if ($_POST['Default'] === "1")
{
	_Html::set_default($_GET['id']);
}

mess_ok("Шаблон «{$html['Identified']} ({$html['Name']})» отредактирован.");
?>