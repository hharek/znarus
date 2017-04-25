<?php
/* Создать индекс */
if ($_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR'])
{
	_Search::create_index();
}
?>
