<?php
/* Проверка сессии */
if (G::session_check() === false)
{
	header("HTTP/1.0 401 Unauthorized");
	throw new Exception("Для доступа к странице требуется аутентификация.");
}

try
{
	/* Идентификатор */
	if (empty($_POST['identified']))
	{
		throw new Exception("Идентификатор не задан.");
	}
	
	/* Получить даты всех версий документа */
	if($_POST['type'] === "date_all")
	{
		$data = 
		[
			"type" => "date_all",
			"date_all" => G::version()->get_date_all($_POST['identified'])
		]; 

	}
	/* Получить версию по указанной дате */
	elseif($_POST['type'] === "data")
	{
		$data = 
		[
			"type" => "data",
			"data" => G::version()->get($_POST['identified'], $_POST['date'])
		]; 
	}
	
	header("Content-type: application/json");
	echo json_encode($data);
}
catch (Exception $e)
{
	header("Content-type: application/json");
	echo json_encode(["error" => $e->getMessage()]);
}
?>