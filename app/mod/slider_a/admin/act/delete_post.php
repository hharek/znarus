<?php
$slider = Slider_A::delete($_GET['id']);

mess_ok("Рисунок слайдера «{$slider['Name']}» удалён.");
redirect("#slider_a/list");
?>