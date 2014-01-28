<?php
$search = 
[
	"<!--zn_meta_title-->",
	"<!--zn_meta_keywords-->",
	"<!--zn_meta_description-->"
];

$replace = 
[
	Reg::meta_title(),
	Reg::meta_keywords(),
	Reg::meta_description()
];

Reg::output(str_replace($search, $replace, Reg::output())); 
?>