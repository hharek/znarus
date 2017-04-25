<?php
G::db_core()->delete("search_log", 
[
	"ID" => $_GET['id']
]);

mess_ok("Удалено");
reload();
?>