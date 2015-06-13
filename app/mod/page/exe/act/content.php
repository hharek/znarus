<?php
$page = Page::get(G::page_id());

title($page['Name']);
tags($page['Tags']);
path
([
	"{$page['Name']} [/{$page['Url']}]"
]);
last_modified($page['Last_Modified']);
?>