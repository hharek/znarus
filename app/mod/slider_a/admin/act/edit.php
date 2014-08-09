<?php
$slider = Slider_A::select_line_by_id($_GET['id']);

title("Рисунок слайдера «{$slider['Name']}»");
path
([
	"Рисунки слайдера [#slider_a/list]",
	"{$slider['Name']} [#slider_a/edit?id={$slider['ID']}]"
]);
?>