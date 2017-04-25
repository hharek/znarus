<?php
title("Другие страницы");
path(["Другие страницы"]);

$html = _Html::get_all();

packjs("codemirror", ["name" => "Home_Content"]);
packjs("codemirror", ["name" => "404_Content"]);
packjs("codemirror", ["name" => "403_Content"]);
?>