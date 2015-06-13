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
	if (empty($_POST['identified']))
	{
		throw new Exception("Не задан идентификатор черновика.");
	}

	/* Получить черновик */
	$draft = G::draft()->get($_POST['identified']);

	header("Content-type: application/json");
	echo json_encode(["draft" => $draft]);
}
catch (Exception $e)
{
	header("Content-type: application/json");
	echo json_encode(["error" => $e->getMessage()]);
}
?>