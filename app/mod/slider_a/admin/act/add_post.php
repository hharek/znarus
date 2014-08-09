<?php
if(empty($_FILES['File']))
{
	Err::add("Рисунок не задан", "File");
	Err::exception();
}

$slider = Slider_A::add($_POST['Name'], $_POST['Url'], $_FILES['File']['tmp_name']);

mess_ok("Рисунок «{$slider['Name']}»");
redirect("#slider_a/list");
?>