<?php
/* Проверка сессии */
if (G::session_check() === false)
{
	header("HTTP/1.0 401 Unauthorized");
	throw new Exception("Для доступа к странице требуется аутентификация.");
}

try
{
	/* Проверка */
	if (empty($_GET['identified']))
	{
		throw new Exception("Не задан идентификатор черновика.");
	}
	
	if (empty($_POST))
	{
		throw new Exception("Не заданы новые данные для черновика.");
	}
	
	/* Назначить новые данные черновику */
	G::draft()->set($_GET['identified'], $_POST);
	
	header("Content-type: application/json");
	echo json_encode(["result" => true]);
}
catch (Exception $e)
{
	header("Content-type: application/json");
	echo json_encode(["error" => $e->getMessage()]);
}
?>