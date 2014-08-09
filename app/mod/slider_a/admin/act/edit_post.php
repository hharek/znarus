<?php
$file = "";
if(!empty($_FILES['File']))
{$file = $_FILES['File']['tmp_name'];}

$slider = Slider_A::edit($_GET['id'], $_POST['Name'], $_POST['Url'], $file);

mess_ok("Рисунок «{$slider['Name']}» отредактирован");
reload();
?>