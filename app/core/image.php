<?php
/**
 * Класс для работы с рисунками
 */
class _Image
{
	/**
	 * Проверка файла на рисунок
	 *
	 * @param string $file
	 */
	public static function is($file)
	{
		if (!is_file($file))
		{
			throw new Exception("Файла «{$file}» не существует");
		}

		if (@getimagesize($file) === false)
		{
			throw new Exception("Файл «{$file}» не является рисунком");
		}
	}

	/**
	 * Получить информацию по рисунку
	 *
	 * @param string $file
	 * @return array
	 */
	public static function info($file)
	{
		/* Проверка */
		self::is($file);
		
		/* Общие настройки */
		$getimagesize = @getimagesize($file);
		$info = [];
		$info['width'] = $getimagesize[0];
		$info['height'] = $getimagesize[1];
		
		/* Тип рисунка */
		switch ($getimagesize[2])
		{
			case IMAGETYPE_GIF:
			{
				$info['type'] = "gif";
			}
			break;

			case IMAGETYPE_JPEG:
			{
				$info['type'] = "jpg";
			}
			break;
		
			case IMAGETYPE_JPEG2000:
			{
				$info['type'] = "jpg";
			}
			break;

			case IMAGETYPE_PNG:
			{
				$info['type'] = "png";
			}
			break;

			case IMAGETYPE_ICO:
			{
				$info['type'] = "ico";
			}
			break;
		
			default:
			{
				$info['type'] = "undefined";
			}
			break;
		}
		
		/* Размер рисунка */
		$info['size'] = filesize($file);

		return $info;
	}

	/**
	 * Проверка на соответствие размерам
	 *
	 * @param string $file
	 * @param string $width (>210, =210, <210)
	 * @param string $height (>210, =210, <210)
	 */
	public static function check_size($file, $width = null, $height = null)
	{
		/* Информация по рисунку */
		$info = self::info($file);

		/* Щирина */
		if ($width !== null)
		{
			self::_check_value("Ширина", $info['width'], $width);
		}

		/* Высота */
		if ($height !== null)
		{
			self::_check_value("Высота", $info['height'], $height);
		}
	}

	/**
	 * Конвертировать рисунок из одного расширения в другой
	 *
	 * @param string $file_in
	 * @param string $file_out
	 */
	public static function convert($file_in, $file_out)
	{
		/* Проверка */
		$file_in_info = self::info($file_in);
		if (!in_array($file_in_info['type'], ["jpg","png","gif"]))
		{
			throw new Exception("Имя входящего файла задано неверно. Допускаются расширения: jpg, png, gif.");
		}
		if (empty($file_out) or ! preg_match("#\.(jpg|png|gif)$#isu", $file_out, $match))
		{
			throw new Exception("Имя исходящего файла задано неверно. Допускаются расширения: jpg, png, gif.");
		}
		$file_out_ext = $match[1];

		/* Простое копирование, если расширения совпадают */
		if ($file_in_info['type'] === $file_out_ext[1])
		{
			if (!@copy($file_in, $file_out))
			{
				throw new Exception(error_get_last()['message']);
			}
			
			return;
		}

		/* Входящий файл */
		switch ($file_in_info['type'])
		{
			case "jpg":
			{
				$im = @imagecreatefromjpeg($file_in);
			}
			break;

			case "png":
			{
				$im = @imagecreatefrompng($file_in);
			}
			break;

			case "gif":
			{
				$im = @imagecreatefromgif($file_in);
			}
			break;
		}

		/* Исходящий файл */
		switch ($file_out_ext)
		{
			case "jpg":
			{
				@imagejpeg($im, $file_out, 100);
			}
			break;

			case "png":
			{
				@imagepng($im, $file_out);
			}
			break;

			case "gif":
			{
				@imagegif($im, $file_out);
			}
			break;
		}
		
		/* Генерим исключение если были ошибки */
		if (error_get_last() !== null)
		{
			throw new Exception(error_get_last()['message']);
		}

		/* Очистим память */
		imagedestroy($im);
	}

	/**
	 * Изменить размер
	 *
	 * @param string $file_in
	 * @param int $width
	 * @param int $height
	 * @param string $method (>,<,=)
	 * @param bool $enlarge
	 */
	public static function resize($file_in, $width, $height, $method = ">", $enlarge = false)
	{
		/* Проверка */
		$info = self::info($file_in);
		$width_in = $info['width'];
		$height_in = $info['height'];

		$width = (int)$width;
		$height = (int)$height;

		$file_out = tempnam(sys_get_temp_dir(), "ti_") . "." . $info['type'];
		
		/* Метод */
		if (!in_array($method, [">", "<", "="]))
		{
			throw new Exception("Метод задан неверно");
		}

		/* Можно ли увеличивать */
		$enlarge = (bool) $enlarge;

		
		/* Увеличивать не надо */
		if ($enlarge === false and $width > $width_in and $height > $height_in)
		{
			$width_out = $width_in;
			$height_out = $height_in;
			if ($file_in !== $file_out)
			{
				if (!@copy($file_in, $file_out))
				{
					throw new Exception("Не удалось создать рисунок «{$file_out}»");
				}
			}

			return $file_out;
		}
		
		/* Уменьшить размер */
		switch ($method)
		{
			/* Не больше заданного размера (пропорции сохранить, срез запретить) */
			case ">":
			{
				if (($width / $width_in) <= ($height / $height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width / $width_in);
				}
				else
				{
					$height_out = $height;
					$width_out = $width_in * ($height / $height_in);
				}
			}
			break;

			/* Не меньше заданного размера (пропорции сохранить, срез разрешить) */
			case "<":
			{
				if (($width / $width_in) >= ($height / $height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width / $width_in);
					$y = ($height_out / 2) - ($height / 2);
					$x = 0;
				}
				else
				{
					$height_out = $height;
					$width_out = $width_in * ($height / $height_in);
					$x = ($width_out / 2) - ($width / 2);
					$y = 0;
				}
			}
			break;

			/* В точности заданный размер */
			case "=":
			{
				$width_out = $width;
				$height_out = $height;
			}
			break;
		}

		/* Входящий файл */
		switch ($info['type'])
		{
			case "jpg":
			{
				$im = imagecreatefromjpeg($file_in);
			}
			break;

			case "png":
			{
				$im = imagecreatefrompng($file_in);
			}
			break;

			case "gif":
			{
				$im = imagecreatefromgif($file_in);
			}
			break;
		}

		/* Изменить размер если метод >, = */
		if ($method === ">" or $method === "=")
		{
			$im_out = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_out, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);
		}
		/* Изменить размер если метод < */
		else
		{
			/* Поготовим к срезу */
			$im_crop = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_crop, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);

			/* Срез */
			$im_out = imagecreatetruecolor($width, $height);
			imagecopy($im_out, $im_crop, 0, 0, $x, $y, imagesx($im_crop), imagesy($im_crop));
			imagedestroy($im_crop);
		}

		/* Исходящий */
		switch ($info['type'])
		{
			case "jpg":
			{
				if (!@imagejpeg($im_out, $file_out, 100))
				{
					throw new Exception("Не удалось создать файл «{$file_out}»");
				}
			}
			break;

			case "png":
			{
				if (!@imagepng($im_out, $file_out))
				{
					throw new Exception("Не удалось создать файл «{$file_out}»");
				}
			}
			break;

			case "gif":
			{
				if (!imagegif($im_out, $file_out))
				{
					throw new Exception("Не удалось создать файл «{$file_out}»");
				}
			}
			break;
		}
		imagedestroy($im_out);
		
		/* Возвращаем имя файла */
		return $file_out;
	}

	/**
	 * Наложить рисунок на рисунок
	 *
	 * @param string $file_in
	 * @param string $file_apply
	 * @param string $file_out
	 * @param int size
	 * @param string align (left,center,right)
	 * @param string valign (top,middle,bottom)
	 * @param int padding
	 * @return bool
	 */
	public static function apply_image($file_in, $file_apply, $file_out, $size = 100, $align = "center", $valign = "middle", $padding = 0)
	{
		/* Проверка */
		$in_info = self::info($file_in);
		$apply_info = self::info($file_apply);

		if (empty($file_out))
		{
			throw new Exception("Не задано имя исходящего файла");
		}

		if (!preg_match("#\.(jpg|png|gif)$#isu", $file_out, $match))
		{
			throw new Exception("Имя исходящего файла задано неверно. Допускаются расширения jpg, png, gif.");
		}
		$ext = $match[1];
		
		$size = intval($size);
		if ($size < 0 or $size > 100)
		{
			throw new Exception("Размер \"{$size}\" задан неверно.");
		}

		if (!in_array($align, ["left", "center", "right"]))
		{
			throw new Exception("Горизонтальное выравнивание \"{$align}\" задано неверно.");
		}

		if (!in_array($valign, ["top", "middle", "bottom"]))
		{
			throw new Exception("Вертикальное выравнивание \"{$valign}\" задано неверно.");
		}

		$padding = abs(intval($padding));

		/* Размер применяемого рисунка */
		$width = $in_info['width'];
		$height = $in_info['height'];
		if ($size < 100)
		{
			$width = $width * ($size / 100);
			$height = $height * ($size / 100);
		}

		$width_apply = $apply_info['width'];
		$height_apply = $apply_info['height'];

		if (($width / $width_apply) <= ($height / $height_apply))
		{
			$width_out = $width;
			$height_out = $height_apply * ($width / $width_apply);
		}
		else
		{
			$height_out = $height;
			$width_out = $width_apply * ($height / $height_apply);
		}

		/* Координаты применяемого рисунка на основном */
		$x = 0;
		$y = 0;

		switch ($align)
		{
			case "left":
			{
				$x = $padding;
			}
			break;

			case "center":
			{
				$x = ($in_info['width'] / 2) - ($width_out / 2);
			}
			break;

			case "right":
			{
				$x = ($in_info['width'] - $width_out) - $padding;
			}
			break;
		}

		switch ($valign)
		{
			case "top":
			{
				$y = $padding;
			}
			break;

			case "middle":
			{
				$y = ($in_info['height'] / 2) - ($height_out / 2);
			}
			break;

			case "bottom":
			{
				$y = ($in_info['height'] - $height_out) - $padding;
			}
			break;
		}

		/* Входящий файл */
		switch ($in_info['type'])
		{
			case "jpg":
			{
				$im_in = imagecreatefromjpeg($file_in);
			}
			break;

			case "png":
			{
				$im_in = imagecreatefrompng($file_in);
			}
			break;

			case "gif":
			{
				$im_in = imagecreatefromgif($file_in);
			}
			break;
		}

		/* Накладываемый файл */
		switch ($apply_info['type'])
		{
			case "jpg":
			{
				$im_apply = imagecreatefromjpeg($file_apply);
			}
			break;

			case "png":
			{
				$im_apply = imagecreatefrompng($file_apply);
			}
			break;

			case "gif":
			{
				$im_apply = imagecreatefromgif($file_apply);
			}
			break;
		}

		/* Наложить */
		imagecopyresampled($im_in, $im_apply, $x, $y, 0, 0, $width_out, $height_out, imagesx($im_apply), imagesy($im_apply));
		imagedestroy($im_apply);

		/* Сохранить */
		switch ($ext)
		{
			case "jpg":
			{
				if (!@imagejpeg($im_in, $file_out, 100))
				{
					throw new Exception("Не удалось создать файл \"{$file_out}\"");
				}
			}
			break;

			case "png":
			{
				if (!@imagepng($im_in, $file_out))
				{
					throw new Exception("Не удалось создать файл \"{$file_out}\"");
				}
			}
			break;

			case "gif":
			{
				if (!imagegif($im_in, $file_out))
				{
					throw new Exception("Не удалось создать файл \"{$file_out}\"");
				}
			}
			break;
		}
		imagedestroy($im_in);
	}

	/**
	 * Вывод рисунка с новыми размерами
	 *
	 * @param string $file_in
	 * @param int $width
	 * @param int $height
	 * @param string $file_name_output
	 * @param bool $download_window
	 * @param string $method (>,<,=)
	 * @return bool
	 */
	public static function output($file_in, $width, $height, $file_name_output = "", $download_window = true, $method = ">")
	{
		/* Проверка */
		$info = self::info($file_in);
		$width_in = $info['width'];
		$height_in = $info['height'];

		$width = (int) $width;
		$height = (int) $height;

		if (empty($file_name_output))
		{
			$file_name_output = basename($file_in);
		}

		if (!preg_match("#\.(jpg|png|gif)$#isu", $file_name_output, $match))
		{
			throw new Exception("Имя исходящего файла задано неверно. Допускаются расширения jpg, png, gif.");
		}
		$ext = $match[1];

		/* Метод */
		if (!in_array($method, [">", "<", "="]))
		{
			throw new Exception("Метод задан неверно");
		}

		/* Уменьшить размер */
		switch ($method)
		{
			/* Не больше заданного размера (пропорции сохранить, срез запретить) */
			case ">":
			{
				if (($width / $width_in) <= ($height / $height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width / $width_in);
				}
				else
				{
					$height_out = $height;
					$width_out = $width_in * ($height / $height_in);
				}
			}
			break;

			/* Не меньше заданного размера (пропорции сохранить, срез разрешить) */
			case "<":
			{
				if (($width / $width_in) >= ($height / $height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width / $width_in);
					$y = ($height_out / 2) - ($height / 2);
					$x = 0;
				}
				else
				{
					$height_out = $height;
					$width_out = $width_in * ($height / $height_in);
					$x = ($width_out / 2) - ($width / 2);
					$y = 0;
				}
			}
			break;

			/* В точности заданный размер */
			case "=":
			{
				$width_out = $width;
				$height_out = $height;
			}
			break;
		}

		/* Входящий файл */
		switch ($info['type'])
		{
			case "jpg":
			{
				$im = imagecreatefromjpeg($file_in);
			}
			break;

			case "png":
			{
				$im = imagecreatefrompng($file_in);
			}
			break;

			case "gif":
			{
				$im = imagecreatefromgif($file_in);
			}
			break;
		}

		/* Изменить размер если метод >, = */
		if ($method == ">" or $method == "=")
		{
			$im_out = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_out, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);
		}
		/* Изменить размер если метод < */
		else
		{
			/* Поготовим к срезу */
			$im_crop = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_crop, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);

			/* Срез */
			$im_out = imagecreatetruecolor($width, $height);
			imagecopy($im_out, $im_crop, 0, 0, $x, $y, imagesx($im_crop), imagesy($im_crop));
			imagedestroy($im_crop);
		}

		/* Заголовок */
		if ($download_window === true)
		{
			header("Content-Type: application/octet-stream;");
			header("Content-Disposition: attachment; filename=\"{$file_name_output}\"");
		}
		else
		{
			switch ($ext)
			{
				case "jpg";
				{
					header('Content-type: image/jpeg');
				}
				break;

				case "png";
				{
					header('Content-type: image/png');
				}
				break;

				case "gif";
				{
					header('Content-type: image/gif');
				}
				break;
			}
		}

		/* Исходящий */
		switch ($ext)
		{
			case "jpg":
			{
				if (!@imagejpeg($im_out, null, 100))
				{
					throw new Exception("Не удалось создать файл «{$file_name_output}»");
				}
			}
			break;

			case "png":
			{
				if (!@imagepng($im_out))
				{
					throw new Exception("Не удалось создать файл «{$file_name_output}»");
				}
			}
			break;

			case "gif":
			{
				if (!@imagegif($im_out))
				{
					throw new Exception("Не удалось создать файл «{$file_name_output}»");
				}
			}
			break;
		}

		imagedestroy($im_out);
	}
	
	/**
	 * Раскрасить изображение (тонировать)
	 * 
	 * @param string $file_in
	 * @param string $color
	 * @param string $file_out
	 */
	public static function colorize($file_in, $color, $file_out)
	{
		/* Проверка */
		$info = self::info($file_in);
		
		if (empty($color))
		{
			throw new Exception("Не указан цвет тонировки.");
		}
		$color = strtolower($color);
		if (!preg_match("#[0-9abcdef]{6}#isu", $color))
		{
			throw new Exception("Цвет тонировки должен быть представлен в виде трёх пар шестнадцатеричных цифр.");
		}
		
		if (empty($file_out))
		{
			throw new Exception("Не задано имя исходящего файла.");
		}
		if (!preg_match("#\.(jpg|png|gif)$#isu", $file_out, $match))
		{
			throw new Exception("Имя исходящего файла задано неверно. Допускаются расширения jpg, png, gif.");
		}
		$file_out_ext = $match[1];
		
		/* Входящий файл */
		switch ($info['type'])
		{
			case "jpg":
			{
				$im_in = imagecreatefromjpeg($file_in);
			}
			break;

			case "png":
			{
				$im_in = imagecreatefrompng($file_in);
			}
			break;

			case "gif":
			{
				$im_in = imagecreatefromgif($file_in);
			}
			break;
		}
		
		/* Обработка цвета */
		self::_imagecolorize($im_in, $color);
		
		/* Исходящий */
		switch ($file_out_ext)
		{
			case "jpg":
			{
				if (!@imagejpeg($im_in, $file_out, 100))
				{
					throw new Exception("Не удалось создать файл «{$file_out}»");
				}
			}
			break;

			case "png":
			{
				if (!@imagepng($im_in, $file_out))
				{
					throw new Exception("Не удалось создать файл «{$file_out}»");
				}
			}
			break;

			case "gif":
			{
				if (!imagegif($im_in, $file_out))
				{
					throw new Exception("Не удалось создать файл «{$file_out}»");
				}
			}
			break;
		}
		imagedestroy($im_in);
	}
	
	/**
	 * Загрузить рисунок, перед этим проверить и обработать
	 * 
	 * @param string $file
	 * @param string $dir
	 * @param int $width
	 * @param int $height
	 * @param string $format
	 * @return string
	 */
	public static function upload($file, $dir, $width = null, $height = null, $format = null)
	{
		/* Проверка */
		self::check_size($file, $width, $height);
		$img_info = _Image::info($file);
		if ($format !== null and $img_info['type'] !== $format)
		{
			throw new Exception("Рисунок должен формата «{$format}»");
		}
		
		$dir = realpath($dir);
		if ($dir === false)
		{
			throw new Exception("Папка «{$dir}» для рисунка указана неверно.");
		}
		
		/* Формируем имя */
		$img_name = substr(md5(microtime()), 0, 6) . "." . $img_info['type'];
		
		/* Загрузить файл */
		copy($file, $dir . "/" . $img_name);
		
		return $img_name;
	}

	/**
	 * Проверка значения на соответствие выражению
	 * 
	 * @param string $name
	 * @param int $value
	 * @param string $str
	 */
	public static function _check_value($name, $value, $str)
	{
		$str = trim($str);
		if (!preg_match("#^(>|>=|<|<=|=)?([0-9]+)$#isu", $str, $match))
		{
			throw new Exception("{$name} «{$str}» задана неверно");
		}

		if (empty($match))
		{
			throw new Exception("{$name} «{$str}» задана неверно");
		}

		/* Проверяемое значение */
		if (is_numeric($str) and empty($match[1]))
		{
			$operation = "=";
		}
		else
		{
			$operation = $match[1];
		}

		$number = (int)strtr($str, ">=<", "   ");
		switch ($operation)
		{
			case ">":
			{
				if (!($value > $number))
				{
					throw new Exception("{$name} должна быть больше «{$number} px.».");
				}
			}
			break;

			case ">=":
			{
				if (!($value >= $number))
				{
					throw new Exception("{$name} должна быть больше, либо равна «{$number} px.».");
				}
			}
			break;

			case "=":
			{
				if (!($value === $number))
				{
					throw new Exception("{$name} должна быть равна «{$number} px.».");
				}
			}
			break;

			case "<":
			{
				if (!($value < $number))
				{
					throw new Exception("{$name} должна быть меньше «{$number} px.».");
				}
			}
			break;

			case "<=":
			{
				if (!($value <= $number))
				{
					throw new Exception("{$name} должна быть меньше, либо равна «{$number} px.».");
				}
			}
			break;
		}
	}
	
	/**
	 * Тонировать изображение (http://php.net/imagecolorset#93134)
	 * 
	 * @param resource $im
	 * @param string $endcolor
	 */
	private static function _imagecolorize(&$im, $endcolor)
	{
		/*
			funcion takes image and turns black into $endcolor, white to white and
			everything in between in corresponding gradient
			$endcolor should be 6 char html color
		*/

		/* make sure it has usable palette */
		if (imageistruecolor($im)) 
		{
			imagetruecolortopalette($im, false, 256);
		}

		/*
			first make it gray to be sure of even results (thanks moxleystratton.com)
			comment this loop if you want the output based on, for example, 
			the red channel (for this take a look at the $gray-var in the last loop)
		*/
		for ($c = 0; $c < imagecolorstotal($im); $c++) 
		{
			$col = imagecolorsforindex($im, $c);
			$gray = round(0.299 * $col['red'] + 0.587 * $col['green'] + 0.114 * $col['blue']);
			imagecolorset($im, $c, $gray, $gray, $gray);
		}

		/* determine end-colors in hexdec */
		$EndcolorRGB['r'] = hexdec( substr($endcolor, 0, 2));
		$EndcolorRGB['g'] = hexdec( substr($endcolor, 2, 2));
		$EndcolorRGB['b'] = hexdec( substr($endcolor, 4, 2));

		/* determine gradient-delta's */
		$stepR = (255-$EndcolorRGB['r'])/255.0;
		$stepG = (255-$EndcolorRGB['g'])/255.0;
		$stepB = (255-$EndcolorRGB['b'])/255.0;

		/* aColor contains the 256 gradations between endcolor(i=0) and white(i=255) */
		$aColor = array();
		for ($i = 0; $i<=255; $i++)
		{
			$aColor[$i]['r'] = $EndcolorRGB['r'] + ($i*$stepR);
			$aColor[$i]['g'] = $EndcolorRGB['g'] + ($i*$stepG);
			$aColor[$i]['b'] = $EndcolorRGB['b'] + ($i*$stepB);
		}

		/* for every color-index we now replace $gray-values for $aColor */
		for ($c = 0; $c < imagecolorstotal($im); $c++)
		{
			$currentColorRGB = imagecolorsforindex($im, $c);
			$gray = $currentColorRGB['red']; /* image is grayscale, so red,green and blue */
			/* should be equal. We use this number as key of aColor */
			imagecolorset($im,$c,(int)$aColor[$gray]['r'], (int)$aColor[$gray]['g'], (int)$aColor[$gray]['b']);
		}
	}
}

?>