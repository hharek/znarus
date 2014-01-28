<?php
/**
 * Группы пользователей
 */
class ZN_User
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $email
	 * @param string $password
	 * @param int $group_id
	 * @param bool $active
	 * @return array
	 */
	public static function add($name, $email, $password, $group_id, $active)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($email, "email", false, "Email", "E-mail");
		
		$password = trim($password);
		Err::check_field($password, "string", false, "Password", "Пароль");
		if(mb_strlen($password) < Reg::password_length_min())
		{Err::add("Пароль не должен быть меньше " . Reg::password_length_min() . " символов.", "Password");}
		if(mb_strlen($password) > Reg::password_length_max())
		{Err::add("Пароль не должен быть больше " . Reg::password_length_max() . " символов.", "Password");}
		
		ZN_User_Group::is_id($group_id);
		Err::check_field($active, "bool", false, "Active", "Активен");
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $email);
		Err::exception();
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Email" => $email,
			"Password" => self::password_hash($password),
			"Group_ID" => $group_id,
			"Active" => $active
		];
		$id = Reg::db_core()->insert("user", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $email
	 * @param int $group_id
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $name, $email, $group_id, $active)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($email, "email", false, "Email", "E-mail");
		ZN_User_Group::is_id($group_id);
		Err::exception();
		Err::check_field($active, "bool", false, "Active", "Активен");
		
		/* Уникальность */
		self::_unique($name, $email, $id);
		Err::exception();
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Email" => $email,
			"Group_ID" => $group_id,
			"Active" => $active
		];
		Reg::db_core()->update("user", $data, array("ID" => $id));
		
		/* Данные изменённого */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		/* Проверка */
		$user = self::select_line_by_id($id);
		
		/* Удалить сессии */
		Reg::db_core()->delete("user_session", array("User_ID" => $id));
		
		/* Удалить */
		Reg::db_core()->delete("user", array("ID" => $id));
		
		/* Данные удалённого */
		return $user;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у пользователя задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"user"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "user");
		if($count < 1)
		{throw new Exception_Admin("Пользователя с номером «{$id}» не существует.");}
	}
	
	/**
	 * Получить хэш пароля
	 * 
	 * @param string $password
	 * @return string
	 */
	public static function password_hash($password)
	{
		/* Соль */
		$salt = Reg::salt_admin();								/* Соль в конфигах */
		$salt = md5($salt);										/* Оставляем только символы a-z 0-9 */
		$salt = substr($salt, 0, 22);							/* В bcrypt используются только первые 22 символа */
		$salt = "$2a$" . Reg::password_bcrypt_cost() . "$" . $salt;		/* Соль для функции crypt ($2a$ = bcrypt) */
		
		/* Получить хэш */
		$hash = crypt($password, $salt);						/* Получаем хэш bcrypt */
		$hash = md5($hash);										/* Делаем в 32 символа и прячим соль */
		
		return $hash;
	}
	
	/**
	 * Назначить пароль
	 * 
	 * @param int $id
	 * @param string $password
	 */
	public static function passwd($id, $password)
	{
		self::is_id($id);
		
		$password = trim($password);
		Err::check_field($password, "string", false, "Password", "Пароль");
		if(mb_strlen($password) < Reg::password_length_min())
		{Err::add("Пароль не должен быть меньше " . Reg::password_length_min() . " символов.", "Password");}
		if(mb_strlen($password) > Reg::password_length_max())
		{Err::add("Пароль не должен быть больше " . Reg::password_length_max() . " символов.", "Password");}
		
		Err::exception();
		
		$data =
		[
			"Password" => self::password_hash($password)
		];
		Reg::db_core()->update("user", $data, array("ID" => $id));
	}
	
	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Email",
	"Group_ID",
	"Active"::int
FROM 
	"user"
WHERE 
	"ID" = $1
SQL;
		$user = Reg::db_core()->query_line($query, $id, "user");
		
		return $user;
	}
	
	/**
	 * Выборка всех по группе
	 * 
	 * @param int $group_id
	 * @return array
	 */
	public static function select_list_by_group_id($group_id)
	{
		$query =
<<<SQL
SELECT
	"ID",
	"Name",
	"Email",
	"Group_ID",
	"Active"::int
FROM 
	"user"
WHERE 
	"Group_ID" = $1
ORDER BY
	"Email" ASC
SQL;
		$user = Reg::db_core()->query_assoc($query, $group_id, "user");
		
		return $user;
	}
	
	/**
	 * Выборка всех
	 * 
	 * @return array
	 */
	public static function select_list()
	{
		$query =
<<<SQL
SELECT 
	"ID", 
	"Name",
	"Email", 
	"Group_ID",
	"Active"::int
FROM 
	"user"
ORDER BY 
	"Email" ASC
SQL;
		$user = Reg::db_core()->query_assoc($query, null, "user");
		return $user;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $email
	 * @param int $id
	 */
	private static function _unique($name, $email, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"user"
WHERE 
	"Name" = $1 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $name, "user");
		if($count > 0)
		{Err::add("Пользователь с полем «Наименование» : «{$name}» уже существует.", "Name");}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"user"
WHERE 
	"Email" = $1 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $email, "user");
		if($count > 0)
		{Err::add("Пользователь с полем «E-mail» : «{$email}» уже существует.", "Email");}
	}
}
?>