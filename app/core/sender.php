<?php
/**
 * Отправитель писем
 */
class _Sender
{
	/**
	 * Отправить сообщение
	 * 
	 * @param string $email
	 * @param string $subject
	 * @param string $message
	 */
	public static function send($email, $subject, $message)
	{
		/* Проверка */
		if (empty($email))
		{
			throw new Exception("Почтовый ящик не указан.");
		}
		if (!Chf::email($email))
		{
			throw new Exception("Почтовый ящик указан неверно. " . Chf::error());
		}

		if (empty($subject))
		{
			throw new Exception("Заголовок сообщения не указан.");
		}
		if (!Chf::string($subject))
		{
			throw new Exception("Заголовок сообщения указан неверно. " . Chf::error());
		}

		if (empty($message))
		{
			throw new Exception("Сообщение не указано.");
		}
		if (!Chf::html($message))
		{
			throw new Exception("Сообщение указано неверно. " . Chf::error());
		}

		/* Отправлять ли  */
		if (SENDER !== true)
		{
			return;
		}

		$phpmailer = new PHPMailer();

		/* Имя отправителя */
		$phpmailer->From = SENDER_FROM;
		$phpmailer->FromName = SENDER_FROM_NAME;

		/* SMTP */
		if (SENDER_SMTP === true)
		{
			$phpmailer->isSMTP();
			$phpmailer->Host = SENDER_SMTP_HOST;
			$phpmailer->Port = SENDER_SMTP_PORT;

			if (SENDER_SMTP_SECURE === true)
			{
				$phpmailer->SMTPSecure = SENDER_SMTP_SECURE_TYPE;
			}

			if (SENDER_SMTP_AUTH === true)
			{
				$phpmailer->Username = SENDER_SMTP_AUTH_USERNAME;
				$phpmailer->Password = SENDER_SMTP_AUTH_PASSWORD;
			}
		}

		/* Сообщение */
		$phpmailer->isHTML(true);
		$phpmailer->CharSet = "UTF-8";
		$phpmailer->Subject = $subject;
		$phpmailer->Body = $message;

		/* Отправка */
		
		$phpmailer->addAddress($email);
		if (!$phpmailer->send())
		{
			throw new Exception("Ошибка при отправке сообщения: " . $phpmailer->ErrorInfo);
		}
	}
}
?>