<?php
require __DIR__ . "/../../init.php";
require "function.php";

_Front_Ajax::check();
_Front_Ajax::cache_get();

if (_Front_Ajax::$_cache_use === false)
{
	_Front_Ajax::data();
	_Front_Ajax::load();
	_Front_Ajax::cache_set();
}

_Front_Ajax::is_modified();
_Front_Ajax::header();
_Front_Ajax::output();


?>