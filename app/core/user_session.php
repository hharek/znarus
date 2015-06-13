<?php
/**
 * Сессии пользователя
 */
class _User_Session
{
	/**
	 * Проверка сессии
	 * 
	 * @param string $in
	 * @return boolean
	 */
	public static function check($in)
	{
		try
		{
			/* Проверить куки */
			if (empty($_COOKIE['_sid']))
			{
				throw new Exception("В куки отсутствует идентификатор сессии.");
			}
			
			/* Данные сессии */
			$session = self::get($in);
			if (empty($session))
			{
				throw new Exception("Данные по сессии отсутствуют.");
			}
			
			/* Проверка данных сессий с данными пользователя */
			if 
			(
				$session['ID'] !== $_COOKIE['_sid'] or
				$session['IP'] !== md5($_COOKIE['_sid'] . SALT . $_SERVER['REMOTE_ADDR']) or 
				$session['Browser'] !== md5($_COOKIE['_sid'] . SALT . $_SERVER['HTTP_USER_AGENT'])
			)
			{
				throw new Exception("Данные сессии не совпадают с вашими данными.");
			}
			
			/* Закончилось ли время хранения */
			if ($in === "admin")
			{
				$time_life = strtotime($session['Date']) + ADMIN_SESSION_TIME;
			}
			elseif ($in === "constr")
			{
				$time_life = strtotime($session['Date']) + CONSTR_SESSION_TIME;
			}
			
			if ($time_life < time())
			{
				throw new Exception("Время хранения сессии истекло.");
			}
			
			return true;
		} 
		catch (Exception $e) 
		{
			return false;
		}
	}
	
	/**
	 * Создать сессию
	 * 
	 * @param string $in
	 * @param string $type (user|root)
	 * @param int $user_id
	 */
	public static function add($in, $type, $user_id)
	{
		/* Проверка */
		if ($in === "constr" and $type !== "root")
		{
			throw new Exception("В конструкторе можно создать сессию только для «" . ROOT_NAME_FULL . "».");
		}
		
		if ($user_id !== null)
		{
			_User::is($user_id);
		}
		
		/* Удаление старой сессии */
		if ($in === "admin")
		{
			if ($type === "user")
			{
				G::db_core()->delete("user_session", ["User_ID" => $user_id]);
			}
			elseif ($type === "root")
			{
				$query = 
<<<SQL
DELETE
FROM 
	"user_session"
WHERE 
	"User_ID" IS NULL
SQL;
				G::db_core()->query($query);
			}			
		}
		
		/* Данные по новой сессии */
		$sid = md5(microtime() . mt_rand(0, 1000000));
		$data = 
		[
			"ID" => $sid,
			"IP" => md5($sid . SALT . $_SERVER['REMOTE_ADDR']),
			"Browser" => md5($sid . SALT . $_SERVER['HTTP_USER_AGENT']),
			"Date" => date("Y-m-d H:i:s")
		];
		
		if ($in === "constr")
		{
			$data['User_ID'] = 0;
			P::set("constr_session", serialize($data));
		}
		elseif ($in === "admin")
		{
			$data['User_ID'] = $user_id;
			G::db_core()->insert("user_session", $data);
		}
		
		/* Создание сессии */
		$_COOKIE['_sid'] = $sid;
		if ($in === "constr")
		{
			setcookie("_sid", $sid, time() + CONSTR_SESSION_TIME, "/" . urlencode(URL_CONSTR));
		}
		elseif ($in === "admin")
		{
			setcookie("_sid", $sid, time() + ADMIN_SESSION_TIME, "/" . urlencode(URL_ADMIN));
		}
	}
	
	/**
	 * Удалить сессию
	 * 
	 * @param type $in
	 */
	public static function delete($in)
	{	
		if (!empty($_COOKIE['_sid']))
		{
			if ($in === "constr")
			{
				P::set("constr_session", "");
				setcookie("_sid", null, time() - 360000, "/" . urlencode(URL_CONSTR));
			}
			elseif ($in === "admin")
			{
				G::db_core()->delete("user_session", ["ID" => $_COOKIE['_sid']]);
				setcookie("_sid", null, time() - 360000, "/" . urlencode(URL_ADMIN));
			}
		}
	}
	
	/**
	 * Получить данные по сессии
	 * 
	 * @param string $in
	 * @return array
	 */
	public static function get($in)
	{
		/* Проверка куки */
		if (empty($_COOKIE['_sid']))
		{
			throw new Exception("В куки отсутствует идентификатор сессии.");
		}
		
		/* По конструктору */
		if ($in === "constr")
		{
			$session = P::get("constr_session");
			if (empty($session))
			{
				return;
			}
			$session = unserialize($session);
			return $session;
		}
		/* По админке */
		elseif ($in === "admin")
		{
			$query = 
<<<SQL
SELECT 
	"ID",
	"Date",
	"IP",
	"Browser",
	COALESCE ("User_ID", 0) as "User_ID"
FROM 
	"user_session"
WHERE 
	"ID" = $1 
SQL;
			return G::db_core()->query($query, $_COOKIE['_sid'])->row();
		}
		
	}
}
?>