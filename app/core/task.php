<?php
/**
 * Задания
 */
class _Task
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у «Задания» задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"task"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Задания с номером «{$id}» не существует.");
		}
	}

	/**
	 * Добавить задание
	 * 
	 * @param string $from
	 * @param string $to
	 * @param string $name
	 * @param string $content
	 * @param string $note
	 * @param string $date_require
	 * @return array
	 */
	public static function add($from, $to, $name, $content, $note, $date_require)
	{
		/* Проверка */
		self::_check($from, $to, $name, $content, $note, null, null, $date_require, null, null);

		/* SQL */
		$data = 
		[
			"From" => (int) $from === 0 ? null : $from,
			"To" => (int) $to === 0 ? null : $to,
			"Name" => $name,
			"Content" => $content,
			"Note" => $note,
			"Date_Require" => empty($date_require) ? null : date("Y-m-d H:i:s", strtotime($date_require))
		];
		$id = G::db_core()->insert("task", $data, "ID");

		return self::get($id);
	}

	/**
	 * Редактировать задание
	 * 
	 * @param int $id
	 * @param int $from
	 * @param int $to
	 * @param string $name
	 * @param string $content
	 * @param string $note
	 * @param string $status
	 * @param string $date_create
	 * @param string $date_require
	 * @param string $date_done
	 * @param string $date_fail
	 * @return array
	 */
	public static function edit ($id, $from, $to, $name, $content, $note, $status, $date_create, $date_require, $date_done, $date_fail)
	{
		/* Проверка */
		self::_check($from, $to, $name, $content, $note, $status, $date_create, $date_require, $date_done, $date_fail, $id);

		/* SQL */
		$data = 
		[
			"From" => (int) $from === 0 ? null : $from,
			"To" => (int) $to === 0 ? null : $to,
			"Name" => $name,
			"Content" => $content,
			"Note" => $note,
			"Status" => $status,
			"Date_Create" => $date_create,
			"Date_Require" => empty($date_require) ? null : date("Y-m-d H:i:s", strtotime($date_require)),
			"Date_Done" => empty($date_done) ? null : date("Y-m-d H:i:s", strtotime($date_done)),
			"Date_Fail" => empty($date_fail) ? null : date("Y-m-d H:i:s", strtotime($date_fail))
		];
		G::db_core()->update("task", $data, ["ID" => $id]);

		return self::get($id);
	}

	/**
	 * Удалить задание
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		$task = self::get($id);

		G::db_core()->delete("task", ["ID" => $id]);

		return $task;
	}

	/**
	 * Сменить статус
	 * 
	 * @param int $id
	 * @param string $note
	 * @param string $status
	 */
	public static function set_status($id, $note, $status)
	{
		self::is($id);

		if (!Chf::text($note))
		{
			throw new Exception("Примечание задано неверно. " . Chf::error());
		}

		if (!in_array($status, ['create', 'done', 'fail']))
		{
			throw new Exception("Статус указан неверно.");
		}

		$data = 
		[
			"Note" => $note,
			"Status" => $status
		];

		switch ($status)
		{
			case "done":
			{
				$data["Date_Done"] = "now()";
			}
			break;

			case "fail":
			{
				$data["Date_Fail"] = "now()";
			}
			break;
		}

		G::db_core()->update("task", $data, ["ID" => $id]);
	}

	/**
	 * Уведомить исполнителя о задании
	 * 
	 * @param int $id
	 */
	public static function send_to($id)
	{
		$task = self::get($id);

		/* Email */
		if ($task['To'] !== "0")
		{
			$email = _User::get($task['To'])['Email'];
		}
		else
		{
			$email = ROOT_EMAIL;
		}

		/* Заголовок */
		if (mb_strlen($task['Name']) > 20)
		{
			$task_name = mb_substr($task['Name'], 0, 20) . " ...";
		}
		else
		{
			$task_name = $task['Name'];
		}
		$subject = "Новое задание «{$task_name}» на сайте " . DOMAIN;

		/* Сообщение */
		ob_start();
		require DIR_APP . "/smod/_task/message/to.html";
		$message = ob_get_contents();
		ob_end_clean();

		/* Отправить */
		_Sender::send($email, $subject, $message);
	}

	/**
	 * Уведомить заказчика об изменении статуса
	 * 
	 * @param int $id
	 */
	public static function send_from_status($id)
	{
		$task = self::get($id);

		/* Email */
		if ($task['From'] !== "0")
		{
			$email = _User::get($task['From'])['Email'];
		}
		else
		{
			$email = ROOT_EMAIL;
		}

		/* Заголовок */
		if (mb_strlen($task['Name']) > 20)
		{
			$task_name = mb_substr($task['Name'], 0, 20) . " ...";
		}
		else
		{
			$task_name = $task['Name'];
		}

		if ($task['Status'] === "done")
		{
			$subject = "Выполнено задание «{$task_name}» на сайте " . DOMAIN;
		}
		elseif ($task['Status'] === "fail")
		{
			$subject = "Получен отказ от задания «{$task_name}» на сайте " . DOMAIN;
		}

		/* Сообщение */
		ob_start();
		require DIR_APP . "/smod/_task/message/from.html";
		$message = ob_get_contents();
		ob_end_clean();

		/* Отправить */
		_Sender::send($email, $subject, $message);
	}

	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function get($id)
	{
		self::is($id);

		$query = 
<<<SQL
SELECT
	"t"."ID",
	COALESCE("t"."From", 0) as "From",
	"u1"."Name" as "From_Name",
	COALESCE("t"."To", 0) as "To",
	"u2"."Name" as "To_Name",
	"t"."Name",
	"t"."Content",
	"t"."Note",
	"t"."Status",
	"t"."Date_Create",
	"t"."Date_Require",
	"t"."Date_Done",
	"t"."Date_Fail"
FROM
	"task" as "t" LEFT JOIN
	"user" as "u1" ON ("t"."From" = "u1"."ID") LEFT JOIN
	"user" as "u2" ON ("t"."To" = "u2"."ID")
WHERE
	"t"."ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Выборка всех заданий по заказчику
	 * 
	 * @param int $from
	 * @return array
	 */
	public static function get_by_from($from)
	{
		if ((int) $from !== 0)
		{
			_User::is($from);
		}
		else
		{
			$from = 0;
		}

		$query = 
<<<SQL
SELECT
	"t"."ID",
	COALESCE("t"."To", 0) as "To",
	"u"."Name" as "To_Name",
	"t"."Name",
	"t"."Status",
	"t"."Date_Create",
	"t"."Date_Require",
	"t"."Date_Done",
	"t"."Date_Fail"
FROM
	"task" as "t" LEFT JOIN 
	"user" as "u" ON ("t"."To" = "u"."ID")
WHERE
	COALESCE("t"."From", 0) = $1
ORDER BY
	"t"."Date_Require" ASC
SQL;
		return G::db_core()->query($query, $from)->assoc();
	}

	/**
	 * Выборка всех заданий по исполнителю
	 * 
	 * @param int $to
	 * @return array
	 */
	public static function get_by_to($to)
	{
		if ((int) $to !== 0)
		{
			_User::is($to);
		}
		else
		{
			$to = 0;
		}

		$query = 
<<<SQL
SELECT
	"t"."ID",
	COALESCE("t"."From", 0) as "From",
	"u"."Name" as "From_Name",
	"t"."Name",
	"t"."Status",
	"t"."Date_Create",
	"t"."Date_Require",
	"t"."Date_Done",
	"t"."Date_Fail"
FROM
	"task" as "t" LEFT JOIN 
	"user" as "u" ON ("t"."From" = "u"."ID")
WHERE
	COALESCE("t"."To", 0) = $1
ORDER BY
	"t"."Date_Require" ASC
SQL;
		return G::db_core()->query($query, $to)->assoc();
	}

	/**
	 * Получить кол-во не выполненных поручений
	 * 
	 * @param int $from
	 * @return int
	 */
	public static function get_count_from_create($from)
	{
		if ((int) $from !== 0)
		{
			_User::is($from);
		}
		else
		{
			$from = 0;
		}

		$query = 
<<<SQL
SELECT
	COUNT(*) as "count"
FROM 
	"task"
WHERE
	COALESCE("From", 0) = $1 AND
	"Status" = 'create'
SQL;
		return (int) G::db_core()->query($query, $from)->single();
	}

	/**
	 * Получить кол-во не выполненных заданий
	 * 
	 * @param int $to
	 * @return int
	 */
	public static function get_count_to_create($to)
	{
		if ((int) $to !== 0)
		{
			_User::is($to);
		}
		else
		{
			$to = 0;
		}

		$query = 
<<<SQL
SELECT
	COUNT(*) as "count"
FROM 
	"task"
WHERE
	COALESCE("To", 0) = $1 AND
	"Status" = 'create'
SQL;
		return (int) G::db_core()->query($query, $to)->single();
	}

	/**
	 * Проверка поручения на принадлежность к пользователю
	 * 
	 * @param int $id
	 * @param int $from
	 */
	public static function is_from($id, $from)
	{
		self::is($id);

		if ((int) $from !== 0)
		{
			_User::is($from);
		}
		else
		{
			$from = 0;
		}

		$query = 
<<<SQL
SELECT
	true
FROM
	"task"
WHERE
	"ID" = $1 AND
	COALESCE("From", 0) = $2
SQL;
		$rec = G::db_core()->query($query, [$id, $from])->single();
		if ($rec === null)
		{
			throw new Exception("Нет прав");
		}
	}

	/**
	 * Проверка задания на принадлежность к пользователю
	 * 
	 * @param int $id
	 * @param int $to
	 */
	public static function is_to($id, $to)
	{
		self::is($id);

		if ((int) $to !== 0)
		{
			_User::is($to);
		}
		else
		{
			$to = 0;
		}

		$query = 
<<<SQL
SELECT
	true
FROM
	"task"
WHERE
	"ID" = $1 AND
	COALESCE("To", 0) = $2
SQL;
		$rec = G::db_core()->query($query, [$id, $to])->single();
		if ($rec === null)
		{
			throw new Exception("Нет прав");
		}
	}

	/**
	 * Проверка полей
	 * 
	 * @param int $from
	 * @param int $to
	 * @param string $name
	 * @param string $content
	 * @param string $note
	 * @param string $status
	 * @param string $date_require
	 * @param int $id
	 */
	private static function _check ($from, $to, $name, $content, $note, $status, $date_create, $date_require, $date_done, $date_fail, $id = null)
	{
		if (!is_null($from))
		{
			if ($from !== "0")
			{
				_User::is($from);
			}
		}

		if (!is_null($to))
		{
			if ($to !== "0")
			{
				_User::is($to);
			}
		}

		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($content, "text", true, "Content", "Описание");
		Err::check_field($note, "text", true, "Note", "Примечание");
		if (!is_null($status))
		{
			if (!in_array($status, ['create', 'done', 'fail']))
			{
				throw new Exception("Статус указан неверно.");
			}
		}
		if (!empty($date_create) and strtotime($date_create) === false)
		{
			Err::add("Поле «Дата создания» указано неверно.", "Date_Create");
		}
		if (!empty($date_require))
		{
			if (strtotime($date_require) === false or strtotime($date_require) < strtotime(date("Y-m-d")))
			{
				Err::add("Поле «Выполнить до» указано неверно.", "Date_Require");
			}
		}
		if (!empty($date_done) and strtotime($date_done) === false)
		{
			Err::add("Поле «Дата выполнения» указано неверно.", "Date_Done");
		}
		if (!empty($date_fail) and strtotime($date_fail) === false)
		{
			Err::add("Поле «Дата отказа» указано неверно.", "Date_Fail");
		}
		Err::exception();

		if (!is_null($id))
		{
			self::is($id);
		}
	}
}
?>