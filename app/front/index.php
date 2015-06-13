<?php
//header("Content-type: text/plain");

require __DIR__ . "/../init.php";
require "function.php";

_Front::redirect();
_Front::url_check();
_Front::route();
_Front::cache_page_get();

if (_Front::$_cache_page === false)
{
	_Front::proc();
	_Front::access();
	_Front::html_set();
	_Front::exe();
	_Front::html();
	_Front::cache_page_set();	
}

_Front::is_modified();
_Front::data();
_Front::header();
_Front::output();
?>